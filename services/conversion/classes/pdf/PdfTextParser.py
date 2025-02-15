import cv2
import numpy as np
from PIL.Image import Image
import pytesseract


class PdfTextParser:
    __font_styles = {}
    __space_sizes = {}

    def is_underline(self, char, rects, skip_start=False):
        for rect in rects:
            if (rect['height'] < 2
                    and ((skip_start == False and rect['x0'] == char['x0']) or skip_start == True)
                    and rect['x1'] >= char['x1']
                    and char['bottom'] - rect['bottom'] <= 1):
                return True
        return False

    def get_char_hyperlink(self, char, hyperlinks):
        for hyperlink in hyperlinks:
            if (
                    hyperlink['top'] <= char['top']
                    and hyperlink['bottom'] >= char['bottom']
                    and hyperlink['x0'] <= char['x0']
                    and hyperlink['x1'] >= char['x1']
            ):
                return hyperlink
        return None

    def is_bold(self, page, char):
        if char['text'] == ' ':
            return False

        font_info = char['fontname'].split('+')
        font_style = font_info[0] if len(font_info) > 1 else None
        fontname_char = font_info[-1]

        if font_style:
            cache_key = font_style + ':' + fontname_char
            if cache_key not in self.__font_styles:
                img_path = self.get_cropped_char_image(page, char)
                avg_thickness = self.get_from_ocr(img_path, 'avg_thickness')
                avg_thickness_ratio = avg_thickness / char['width']

                self.__font_styles[cache_key] = {
                    'bold': avg_thickness_ratio >= 4,
                }
            return self.__font_styles[cache_key]['bold']
        else:
            if "Bold" in char['fontname']:
                return True

        return False

    def is_within_margin(self, value, target, margin):
        return abs(value - target) <= margin

    def get_thickness(self, image_path, isLast=False):
        image = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)
        _, binary = cv2.threshold(image, 128, 255, cv2.THRESH_BINARY_INV)
        contours, _ = cv2.findContours(binary, cv2.RETR_EXTERNAL, cv2.CHAIN_APPROX_SIMPLE)

        if not contours:
            if not isLast:
                self.binarize_using_saturation(image_path)
                return self.get_thickness(image_path, True)
            print("Контуры не найдены.")
            return False

        thicknesses = []
        for contour in contours:
            x, y, w, h = cv2.boundingRect(contour)

            if w > 6 and h > 6 and h / w < 5:  # Условие для фильтрации
                thicknesses.append(w)

        if not thicknesses:
            print("Подходящие символы не найдены.")
            return False

        avg_thickness = sum(thicknesses) / len(thicknesses)
        print(f"Средняя толщина символов: {avg_thickness}")

        return avg_thickness

    def get_text_ocr(self, image_path):
        image = Image.open(image_path)
        text = pytesseract.image_to_string(image, config="--psm 10")
        return text.strip()

    def get_from_ocr(self, image_path, key=None):
        result = {}
        if not key or key == 'avg_thickness':
            result['avg_thickness'] = self.get_thickness(image_path)
        if not key or key == 'text':
            result['text'] = self.get_text_ocr(image_path)

        return result[key] if key else result

    def binarize_using_saturation(self, image_path):
        image = cv2.imread(image_path, cv2.IMREAD_GRAYSCALE)

        # Адаптивная бинаризация
        binary_image = cv2.adaptiveThreshold(
            image,
            255,
            cv2.ADAPTIVE_THRESH_GAUSSIAN_C,
            cv2.THRESH_BINARY_INV,
            11,
            2
        )
        binary_image = cv2.bitwise_not(binary_image)
        # Сохраняем результат
        cv2.imwrite(image_path, binary_image)
        print(f"Бинаризированное изображение сохранено в {image_path}")

    def get_cropped_char_image(self, page, char, res=350):
        img_path = f"tmp/cropped/img.png"
        cropped = page.within_bbox([
            char['x0'],
            char['top'],
            char['x1'],
            char['bottom'] + 5
        ])
        char_image = cropped.to_image(resolution=res)
        char_image.save(img_path)
        return img_path

    def get_hyperlinks(self, page):
        hyperlinks = page.hyperlinks
        annots = page.annots
        for annot in annots:
            if annot['data']['Subtype'].name == 'Link':
                hyperlinks.append(annot)

        return hyperlinks

    def set_bbox_relative(self, obj, bbox):
        obj['bbox'] = {}
        obj['bbox']['x0'] = obj['x0'] - bbox[0]
        obj['bbox']['x1'] = obj['bbox']['x0'] + obj['width']
        obj['bbox']['top'] = obj['top'] - bbox[1]
        obj['bbox']['bottom'] = obj['bbox']['top'] + obj['height']
        return obj

    def prepare_text2(self, page, bbox=None):
        chars = page.chars
        rects = page.rects
        hyperlinks = self.get_hyperlinks(page)
        top = None
        fontsize = None
        fontname = None
        color = None
        stroking_color = None
        is_underline = None
        hyperlink = None
        paragraphs = {}
        run_number = 0
        is_bold = None
        x1 = None
        key = None
        height = None
        for index, char in enumerate(chars):
            if char['tag'] == 'Artifact' or char['text'] == ' ':
                continue

            char['bbox'] = {'x0': char['x0'], 'top': char['top'], 'x1': char['x1'], 'bottom': char['bottom']}
            if bbox is not None:
                char = self.set_bbox_relative(char, bbox)

            if key is None:
                key = index

            if top is None:
                top = char['top']

            if height is None:
                height = char['height']

            if top != char['top'] and (char['top'] - top) > height * 0.3:
                if key in paragraphs:
                    for run in paragraphs[key]['runs']:
                        print(run['text'])
                key = index
                top = char['top']

            if x1 is None:
                x1 = char['x1']

            if is_bold is None:
                is_bold = self.is_bold(page, char)

            if is_underline is None:
                is_underline = self.is_underline(char, rects)

            if hyperlink is None:
                hyperlink = self.get_char_hyperlink(char, hyperlinks)

            if fontsize is None:
                fontsize = round(char['size'])

            if color is None:
                color = char['non_stroking_color']

            if stroking_color is None:
                stroking_color = char['stroking_color']

            if fontname is None:
                fontname = char['fontname'].split('+')[-1]

            fontsize_char = round(char['size'])
            fontname_char = char['fontname'].split('+')[-1]
            hyperlink_char = self.get_char_hyperlink(char, hyperlinks)
            underline_char = self.is_underline(char, rects, is_underline)
            bold_char = self.is_bold(page, char)
            height = char['height']

            if (
                    (char['text'] != ' ' and fontsize != fontsize_char)
                    or (char['text'] != ' ' and color != char['non_stroking_color'])
                    or (char['text'] != ' ' and char['x0'] - x1 > 2)
                    or (char['text'] != ' ' and fontname != fontname_char)
                    or (char['text'] != ' ' and is_underline != underline_char)
                    #or (char['text'] != ' ' and hyperlink != hyperlink_char)
                    or (char['text'] != ' ' and is_bold != bold_char)
                    or (char['text'] != ' ' and stroking_color != char['stroking_color'])
            ):
                print("Символ", char['text'])
                print("fontsize != fontsize_char", fontsize, fontsize_char)
                print("color != char['non_stroking_color']", color, char['non_stroking_color'])
                print("fontname != fontname_char", fontname, fontname_char)
                print("is_underline != underline_char", is_underline, underline_char)
                #print("hyperlink != hyperlink_char", hyperlink, hyperlink_char)
                print("is_bold != bold_char", is_bold, bold_char)
                print("stroking_color != char['stroking_color']", stroking_color, char['stroking_color'])
                run_number = run_number + 1

            x1 = char['x1']

            if fontsize != fontsize_char:
                fontsize = fontsize_char

            if color != char['non_stroking_color']:
                color = char['non_stroking_color']

            if fontname != fontname_char:
                fontname = fontname_char

            if stroking_color != char['stroking_color']:
                stroking_color = char['stroking_color']

            if is_underline != underline_char:
                is_underline = underline_char

            if is_bold != bold_char:
                is_bold = bold_char

            if key not in paragraphs:
                run_number = 0
                paragraphs[key] = {
                    'top': char['bbox']['top'],
                    'bottom': char['bbox']['bottom'],
                    'x0': char['bbox']['x0'],
                    'y0': char['y0'],
                    'y1': char['y1'],
                    'x1': char['bbox']['x1'],
                    'line_spacing': 1,
                    'size': fontsize,
                    'runs': []
                }
            if len(paragraphs[key]['runs']) < run_number + 1:
                paragraphs[key]['runs'].append({
                    'text': '',
                    'fontsize': fontsize_char,
                    'fontname': fontname_char,
                    'stroking_color': stroking_color,
                    'underline': is_underline,
                    'bold': bold_char,
                    'color': color,
                    'x0': char['bbox']['x0'],
                    'top': char['bbox']['top'],
                    'hyperlink': hyperlink,
                })

                if paragraphs[key]['size'] < fontsize:
                    paragraphs[key]['size'] = fontsize

            paragraphs[key]['x1'] = char['bbox']['x1']
            paragraphs[key]['bottom'] = char['bbox']['bottom']

            if not char['text'].isprintable():
                image_path = self.get_cropped_char_image(page, char)
                char['text'] = self.get_from_ocr(image_path, 'text')

            paragraphs[key]['runs'][run_number]['text'] += char['text']

        return [value for key, value in sorted(paragraphs.items())]

    def prepare_text(self, page, bbox=None):
        self.set_space_size(page)
        chars = page.chars
        hyperlinks = self.get_hyperlinks(page)
        paragraphs = {}
        current_paragraph_key = None
        current_run_index = 0
        prev_char = None

        # Переменные для сравнения параметров символов
        current_top = None
        current_height = None
        l_spacing = []
        current_run_attributes = {
            "fontsize": None,
            "fontname": None,
            "color": None,
            "stroking_color": None,
            "bold": None,
            "underline": None,
            "hyperlink": None,
        }

        for char in chars:
            if char['tag'] == 'Artifact' or char['text'] == ' ':
                continue

            char['bbox'] = {'x0': char['x0'], 'top': char['top'], 'x1': char['x1'], 'bottom': char['bottom']}

            if bbox is not None:
                char = self.set_bbox_relative(char, bbox)

            # Начало нового параграфа, если координаты Y изменились значительно
            if current_top is None or abs(char['top'] - current_top) > (current_height or char['height']) * 0.3:
                current_paragraph_key = len(paragraphs)
                current_run_index = 0
                current_top = char['top']
                current_height = char['height']

                paragraphs[current_paragraph_key] = {
                    'top': char['bbox']['top'],
                    'bottom': char['bbox']['bottom'],
                    'x0': char['bbox']['x0'],
                    'x1': char['bbox']['x1'],
                    'line_spacing': 1,
                    'size': round(char['size']),
                    'runs': []
                }

                current_run_attributes = {
                    "fontsize": None,
                    "fontname": None,
                    "color": None,
                    "stroking_color": None,
                    "bold": None,
                    "underline": None,
                    "hyperlink": None,
                }
                l_spacing = []
                prev_char = None

            # Определяем атрибуты текущего символа
            char_attributes = {
                "fontsize": round(char['size']),
                "fontname": char['fontname'].split('+')[-1],
                "color": char['non_stroking_color'],
                "stroking_color": char['stroking_color'],
                "bold": self.is_bold(page, char),
                "underline": self.is_underline(char, page.rects),
                "hyperlink": self.get_char_hyperlink(char, hyperlinks),
            }

            if char_attributes != current_run_attributes:
                paragraphs[current_paragraph_key]['runs'].append({
                    'text': '',
                    'fontsize': char_attributes['fontsize'],
                    'fontname': char_attributes['fontname'],
                    'color': char_attributes['color'],
                    'stroking_color': char_attributes['stroking_color'],
                    'bold': char_attributes['bold'],
                    'underline': char_attributes['underline'],
                    'hyperlink': char_attributes['hyperlink'],
                    'letter_spacing': 0,
                })
                current_run_index += 1
                current_run_attributes = char_attributes

            if len(paragraphs[current_paragraph_key]['runs']) >= 1:
                space = ''

                if prev_char is not None:
                    if char['x0'] - prev_char['x1'] > prev_char['width'] * 0.2:
                        space = self.get_space(prev_char, char)
                    else:
                        l_spacing.append(char['x0'] - prev_char['x1'])

                paragraphs[current_paragraph_key]['runs'][current_run_index - 1]['text'] += space + char['text']
                paragraphs[current_paragraph_key]['runs'][current_run_index - 1]['letter_spacing'] = np.mean(l_spacing)

            # Обновляем границы параграфа
            paragraphs[current_paragraph_key]['x1'] = max(paragraphs[current_paragraph_key]['x1'], char['bbox']['x1'])
            paragraphs[current_paragraph_key]['bottom'] = max(paragraphs[current_paragraph_key]['bottom'],
                                                              char['bbox']['bottom'])

            prev_char = char

        # Возвращаем список параграфов
        return list(paragraphs.values())



    def set_space_size(self, page):
        chars = page.chars
        for char in chars:
            if char['text'] == ' ':
                key = f"{char['fontname']}_{char['size']}"
                if not key in self.__space_sizes:
                    self.__space_sizes[key] = char['width']

    def get_space(self, prev_char, char):
        if not prev_char or not char:
            return ''

        key = f"{char['fontname']}_{char['size']}"

        gap = char['x0'] - prev_char['x1']

        if gap <= 0:
            return ''

        space_size = self.__space_sizes.get(key, prev_char['width'] / 2)

        if key not in self.__space_sizes:
            self.__space_sizes[key] = space_size

        space_count = max(0, round(gap / space_size))

        return ' ' * space_count



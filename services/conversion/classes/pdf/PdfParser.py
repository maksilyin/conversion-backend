import base64
import io
import os
import sys

import cv2
import fitz
import numpy as np
import pdfminer
import pdfplumber
import zlib
from PIL import Image
from docx.shared import Cm
import logging
import pytesseract
from pip._internal import resolution

from classes.pdf.PdfTextParser import PdfTextParser


class PdfParser:
    __font_styles = {}
    __text = []
    __images = []
    __rects = []
    __full_page_rects = []

    def filter_objects(self, obj):
        for rect in self.__rects:
            if (
                    obj['x0'] >= rect['x0']
                    and obj['x1'] <= rect['x1']
                    and obj['top'] >= rect['top']
                    and obj['bottom'] <= rect['bottom']
            ):
                return False
        return True

    def get_data(self, page):
        self.__text_parser = PdfTextParser()
        self.__rects = self.prepare_rects(page)
        self.__full_page_rects = self.prepare_full_page_rects(page)
        filtered_page = page.filter(self.filter_objects)
        self.__text = self.__text_parser.prepare_text(filtered_page)
        self.__images = filtered_page.images

        return {
            'images': self.__images,
            'rects': self.__rects,
            'full_page_rects': self.__full_page_rects,
            'text': self.__text,
        }

    def get_image(self, image_data):
        width, height, colorspace = image_data.attrs["Width"], image_data.attrs["Height"], image_data.attrs[
            "ColorSpace"]
        mode = None
        icc_profile = None
        img = None

        if isinstance(colorspace, list):
            if colorspace[0].name == "ICCBased" and len(colorspace) > 1:
                icc_ref = colorspace[1]
                if hasattr(icc_ref, "resolve"):
                    icc_profile = icc_ref.resolve().get_data()
            colorspace = colorspace[0].name
        elif hasattr(colorspace, "name"):
            colorspace = colorspace.name

        match colorspace:
            case "ICCBased" | "DeviceRGB":
                mode = "RGB"
            case "DeviceGray":
                mode = "L"
            case "DeviceCMYK":
                mode = "CMYK"

        raw_data = image_data.get_data()

        filters = image_data.attrs.get("Filter", [])

        if not isinstance(filters, list):
            filters = [filters]

        for filter in filters:
            if filter.name == "ASCII85Decode":
                try:
                    raw_data = base64.a85decode(raw_data)
                    print("Декодировано с ASCII85Decode")
                except Exception as e:
                    print(f"Ошибка декодирования ASCII85Decode: {e}")
            if filter.name == "DCTDecode":
                try:
                    img = Image.open(io.BytesIO(raw_data))  # DCTDecode — это JPEG
                    print("Изображение успешно извлечено через DCTDecode")
                    return img
                except Exception as e:
                    print(f"Ошибка обработки DCTDecode: {e}")
                    return None

        if mode:
            try:
                img = Image.frombytes(mode, (width, height), raw_data)
            except Exception as e:
                print(f"Ошибка при создании изображения: {e}")

        if "SMask" in image_data:
            mask = self.get_image(image_data['SMask'].resolve())
            img.putalpha(mask)

        return img

    def is_within_margin(self, value, target, margin):
        return abs(value - target) <= margin

    def prepare_full_page_rects(self, page):
        rects = page.rects
        result = {}
        prev_index = None
        for index, rect in enumerate(rects):
            if (
                    self.is_within_margin(rect['height'], page.height, 10)
                    and self.is_within_margin(rect['width'], page.width, 10)
            ):

                if not prev_index is None:
                    prev_rect = result[prev_index]

                    if (
                            self.is_within_margin(prev_rect['x0'], rect['x0'], 1)
                            and self.is_within_margin(prev_rect['x1'], rect['x1'], 1)
                            and self.is_within_margin(prev_rect['y0'], rect['y0'], 1)
                            and self.is_within_margin(prev_rect['y1'], rect['y1'], 1)
                    ):
                        result[prev_index]['stroking_color'] = rect['stroking_color'] if rect['stroke'] else prev_rect['stroking_color']
                        result[prev_index]['dash'] = rect['dash'] if rect['stroke'] else prev_rect['dash']
                        result[prev_index]['height'] = rect['height'] if rect['height'] > prev_rect['height'] else prev_rect['height']
                        result[prev_index]['width'] = rect['width'] if rect['width'] > prev_rect['width'] else prev_rect['width']
                        result[prev_index]['top'] = rect['top'] if rect['top'] < prev_rect['top'] else prev_rect['top']
                        result[prev_index]['bottom'] = rect['bottom'] if rect['bottom'] > prev_rect['bottom'] else prev_rect['bottom']
                        result[prev_index]['non_stroking_color'] = rect['non_stroking_color'] if rect['fill'] else prev_rect['non_stroking_color']
                        continue

                result[index] = rect
                prev_index = index
        return [value for key, value in sorted(result.items())]

    def prepare_rects(self, page):
        rects = page.rects
        result = {}
        prev_index = None
        for index, rect in enumerate(rects):
            if rect['height'] <=1:
                continue

            if (
                    self.is_within_margin(rect['height'], page.height, 10)
                    and self.is_within_margin(rect['width'], page.width, 10)
            ):
                continue

            rect = self.set_text_inside_rect(rect, page)

            if not prev_index is None:
                prev_rect = result[prev_index]

                if (
                        self.is_within_margin(prev_rect['x0'], rect['x0'], 1)
                        and self.is_within_margin(prev_rect['x1'], rect['x1'], 1)
                        and self.is_within_margin(prev_rect['y0'], rect['y0'], 1)
                        and self.is_within_margin(prev_rect['y1'], rect['y1'], 1)
                ):
                    result[prev_index]['stroking_color'] = rect['stroking_color'] if rect['stroke'] else prev_rect['stroking_color']
                    result[prev_index]['dash'] = rect['dash'] if rect['stroke'] else prev_rect['dash']
                    result[prev_index]['height'] = rect['height'] if rect['height'] > prev_rect['height'] else prev_rect['height']
                    result[prev_index]['width'] = rect['width'] if rect['width'] > prev_rect['width'] else prev_rect['width']
                    result[prev_index]['top'] = rect['top'] if rect['top'] < prev_rect['top'] else prev_rect['top']
                    result[prev_index]['bottom'] = rect['bottom'] if rect['bottom'] > prev_rect['bottom'] else prev_rect['bottom']
                    result[prev_index]['non_stroking_color'] = rect['non_stroking_color'] if rect['fill'] else prev_rect['non_stroking_color']
                    continue

            result[index] = rect
            prev_index = index
        return [value for key, value in sorted(result.items())]

    def set_text_inside_rect(self, rect, page):
        bbox = [rect['x0'], rect['top'], rect['x1'], rect['bottom']]
        crop = page.crop(bbox)
        rect['text'] = self.__text_parser.prepare_text(crop, bbox)
        #sys.exit(0)
        return rect

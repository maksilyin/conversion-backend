import os
import subprocess
from os.path import basename

from htmldocx import HtmlToDocx
from html2docx import html2docx
from abc import ABC

from bs4 import BeautifulSoup

from classes.ConverterBase import ConverterBase

class DocumentConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = os.path.splitext(output_path)[1].lstrip('.').lower()

        try:
            self.convert_html_to_docx(file_path, output_path)

        except Exception as e:
            raise Exception(f"Error converting document: {str(e)}")

        return output_path

    def _convert_with_libreoffice(self, file_path: str, output_path: str):
        """Convert document using LibreOffice."""
        output_dir = os.path.dirname(output_path)
        expected_output_file = os.path.join(output_dir, os.path.basename(file_path).rsplit('.', 1)[0] +
                                            os.path.splitext(output_path)[1])

        try:
            # Запуск LibreOffice для конвертации
            result = subprocess.run([
                "libreoffice", "--headless", "--convert-to", os.path.splitext(output_path)[1][1:], "--outdir",
                output_dir, file_path
            ], check=True, capture_output=True, text=True)

            # Вывод отладки LibreOffice
            print("LibreOffice output:", result.stdout)
            print("LibreOffice errors:", result.stderr)

            # Проверка создания файла
            if os.path.exists(expected_output_file):
                os.rename(expected_output_file, output_path)
            else:
                raise Exception(f"Converted file not found: {expected_output_file}")
        except subprocess.CalledProcessError as e:
            raise Exception(f"LibreOffice conversion failed: {e.stderr}")

    def convert_html_to_docx(self, html_path, output_path):
        """
        Конвертирует HTML-файл в DOCX.
        :param html_path: Путь к HTML-файлу
        :param output_path: Путь для сохранения DOCX
        """
        with open(html_path, "r", encoding="utf-8") as file:
            html_content = file.read()

        buf = html2docx(html_content, basename(output_path))

        with open(output_path, "wb") as fp:
            fp.write(buf.getvalue())

        print(f"HTML успешно преобразован в DOCX: {output_path}")

    def _convert_with_pandoc(self, file_path: str, output_path: str):
        """Convert document using Pandoc."""
        try:
            subprocess.run([
                "pandoc", file_path, "-o", output_path
            ], check=True)
        except subprocess.CalledProcessError as e:
            raise Exception(f"Pandoc conversion failed: {str(e)}")

    def _convert_with_imagemagick(self, file_path: str, output_path: str):
        """Convert PDF to image using ImageMagick."""
        try:
            subprocess.run([
                "convert", file_path, output_path
            ], check=True)
        except subprocess.CalledProcessError as e:
            raise Exception(f"ImageMagick conversion failed: {str(e)}")

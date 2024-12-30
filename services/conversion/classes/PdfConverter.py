import os
import subprocess
from abc import ABC
import fitz  # PyMuPDF
from bs4 import BeautifulSoup
from classes.ConverterBase import ConverterBase
from pdf2docx import Converter
from pdf2docx import parse

class PdfConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = os.path.splitext(output_path)[1].lstrip('.').lower()

        try:
            if extension == "docx":
                self._convert_pdf_to_docx(file_path, output_path)
            elif extension in ["png", "jpg", "jpeg", "tiff"]:
                self._convert_pdf_to_image(file_path, output_path)
            elif extension in ["epub", "html", "txt"]:
                self._convert_pdf_to_text_based_format(file_path, output_path)
            else:
                raise Exception(f"Unsupported conversion format: {extension}")
        except Exception as e:
            raise Exception(f"Error converting PDF: {str(e)}")

        return output_path

    def pdf_to_html(self, file_path):
        pdf_document = fitz.open(file_path)
        html_content = ""
        print(pdf_document.page_count)
        for page_num in range(pdf_document.page_count):
            print(page_num)
            page = pdf_document[page_num]
            print(page)
            text = page.get_text("text")
            print(text)
            html_content += f"{text}"
        pdf_document.close()
        return html_content

    def generate_html_markup(self, html_content):
        soup = BeautifulSoup(html_content, "html.parser")
        styled_html = soup.prettify()
        return styled_html

    def _convert_pdf_to_image(self, file_path: str, output_path: str):
        """Convert PDF to image using ImageMagick."""
        try:
            subprocess.run([
                "convert", file_path, output_path
            ], check=True)
        except subprocess.CalledProcessError as e:
            raise Exception(f"PDF to image conversion failed: {str(e)}")

    def _convert_pdf_to_text_based_format(self, file_path: str, output_path: str):
        """Convert PDF to text-based format using Pandoc."""
        try:
            subprocess.run([
                "pandoc", file_path, "-o", output_path
            ], check=True)
        except subprocess.CalledProcessError as e:
            raise Exception(f"PDF to text-based format conversion failed: {str(e)}")

    def convert_pdf_to_docx(self, file_path: str, output_path: str):
        print(file_path)
        print(output_path)

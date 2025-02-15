import os
import re
import subprocess
import sys
import time
from abc import ABC
from classes.ConverterBase import ConverterBase
from libs.adobe_pdfservices import adobe_pdfservices
from libs.archives import create_new_archive
from libs.calibre_converter import calibre
from libs.helpers import get_file_type_by_format
from libs.libreoffice_converter import libreoffice
from pathlib import Path

from libs.pdf2htmlEX import pdf2htmlEX_convert
from libs.ssconvert import ssconvert_convert


class PdfConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = self._output_format
        output_types = get_file_type_by_format(extension)

        try:
            if extension == "docx" or extension == "doc" or extension == "xlsx" or extension == "pptx":
                output_path = self._convert_pdf(file_path, output_path, extension)
            elif self._output_format == "html":
                output_path = self._pdf_to_html(file_path, output_path)
            elif 'presentation' in output_types:
                output_path = self._convert_pdf_to_presentation(file_path, output_path, extension)
            elif 'image' in output_types:
                output_path = self._convert_pdf_to_image(file_path, output_path)
            elif 'spreadsheets' in output_types:
                output_path = self._convert_pdf_to_spreadsheets(file_path, output_path)
            elif 'ebook' in output_types:
                output_path = calibre(file_path, output_path)
            else:
                output_path = self._convert_pdf_to_document(file_path, output_path)
        except Exception as e:
            raise Exception(f"Error converting PDF: {str(e)}")
        finally:
            self._delete_tmp_dir()

        return output_path

    def _pdf_to_html(self,  file_path: str, output_path: str):
        output_dir = os.path.dirname(output_path)
        try:
            result = pdf2htmlEX_convert(file_path, output_dir)
            if not result:
                raise Exception(f"PDF to html conversion failed")
            return output_path
        except Exception as e:
            raise Exception(str(e))

    def _convert_pdf_to_image(self, file_path: str, output_path: str):
        tmp_file = Path(self._file_path).stem
        tmp_file = re.sub(r"^[a-f0-9-]+_", "", tmp_file, count=1)
        tmp_file = self._create_tmp_dir(build_path=f"{tmp_file}.{self._output_format}")
        try:
            subprocess.run([
                "convert", file_path, tmp_file
            ], check=True)

            output_path = Path(output_path).with_suffix(".zip")
            create_new_archive(self._tmp_path, output_path)
            return str(output_path)
        except subprocess.CalledProcessError as e:
            raise Exception(f"PDF to image conversion failed: {str(e)}")

    def _convert_pdf_to_spreadsheets(self, file_path: str, output_path: str):
        tmp_xlsx = self._create_tmp_dir(build_path=f"{Path(self._file_path).stem}.xlsx")
        print("Converting PDF to xlsx")
        time.sleep(1)
        tmp_xlsx_output = self._convert_pdf(file_path, tmp_xlsx, 'xlsx')

        if tmp_xlsx_output:
            print(f"Converting PDF to xlsx is successful: {tmp_xlsx_output}")
            file_path = tmp_xlsx_output

        return ssconvert_convert(file_path, output_path)

    def _convert_pdf_to_document(self, file_path: str, output_path: str):
        tmp_docx = self._create_tmp_dir(build_path=f"{Path(self._file_path).stem}.docx")
        print("Converting PDF to docx")
        time.sleep(1)
        tmp_docx_output = self._convert_pdf(file_path, tmp_docx, 'docx')

        if tmp_docx_output:
            print(f"Converting PDF to docx is successful: {tmp_docx_output}")
            file_path = tmp_docx_output

        return calibre(file_path, output_path)

    def _convert_pdf_to_presentation(self, file_path: str, output_path: str, extension: str):
        tmp_pptx = self._create_tmp_dir(build_path=f"{Path(self._file_path).stem}.pptx")
        print("Converting PDF to pptx")
        time.sleep(1)
        tmp_pptx_output = self._convert_pdf(file_path, tmp_pptx, 'pptx')

        if tmp_pptx_output:
            print(f"Converting PDF to xlsx is successful: {tmp_pptx_output}")
            file_path = tmp_pptx_output

        return libreoffice(file_path, output_path, extension)

    def _convert_pdf(self, file_path: str, output_path: str, extension: str):
        return adobe_pdfservices(file_path, output_path, extension)

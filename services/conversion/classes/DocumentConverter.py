import sys
from pathlib import Path
from abc import ABC
from classes.ConverterBase import ConverterBase
from libs.Converter import convert_file
#from libs.Converter import convert_file
from libs.calibre_converter import calibre
from libs.helpers import get_file_type_by_format
from libs.libreoffice_converter import libreoffice
from libs.wkhtmltopdf import wkhtmltopdf


class DocumentConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        output_types = get_file_type_by_format(self._output_format)
        print(output_types)
        try:
            if self.isHtml() and not self._output_format == 'txt':
                if self._output_format == 'pdf':
                    wkhtmltopdf(file_path, output_path)
                elif "image" in output_types:
                    tmp_pdf = self._create_tmp_dir(build_path=f"{Path(file_path).stem}.pdf")
                    wkhtmltopdf(file_path, tmp_pdf)
                    convert_file('pdf', tmp_pdf, output_path)
                else:
                    tmp_pdf = self._create_tmp_dir(build_path=f"{Path(file_path).stem}.pdf")
                    wkhtmltopdf(file_path, tmp_pdf)
                    calibre(tmp_pdf, output_path)
                    #convert_file('pdf', tmp_pdf, output_path)
            elif self.isDocx():
                if self._output_format == 'doc' or self._output_format == 'pdf':
                    libreoffice(file_path, output_path, self._output_format)
                elif self._output_format == 'html' or 'presentation' in output_types or 'image' in output_types or 'spreadsheets' in output_types:
                    tmp_pdf = self._create_tmp_dir(build_path=f"{Path(file_path).stem}.pdf")
                    libreoffice(file_path, tmp_pdf, 'pdf')
                    output_path = convert_file('pdf', tmp_pdf, output_path)
                else:
                    calibre(file_path, output_path)

            else:
                calibre(self._file_path, output_path)
        except Exception as e:
            raise Exception(f"Error converting document: {str(e)}")
        finally:
            self._delete_tmp_dir()

        return output_path

    def isHtml(self) -> bool:
        return self._mime_type == 'text/html'

    def isDocx(self) -> bool:
        return self._mime_type == 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'


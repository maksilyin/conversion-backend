import os
import subprocess
from abc import ABC
from classes.ConverterBase import ConverterBase
from libs.adobe_pdfservices import adobe_pdfservices


class PresentationConverter(ConverterBase, ABC):
    _tmp_created = False
    def convert(self) -> str:
        output_path = self._get_output_path()
        output_dir = os.path.abspath(os.path.dirname(output_path))
        try:
            if self._output_format == 'docx' or self._output_format == 'doc':
                self._create_tmp_dir()
                subprocess.run(["libreoffice", "--headless", "--convert-to", 'pdf', self._file_path, "--outdir", self._tmp_path], check=True)
                pdf_name = os.path.splitext(os.path.basename(self._file_path))[0] + ".pdf"
                tmp_file = f"{self._tmp_path}/{pdf_name}"
                adobe_pdfservices(tmp_file, output_path, self._output_format)
            else:
                subprocess.run(["libreoffice", "--headless", "--convert-to", self._output_format, self._file_path, "--outdir", output_dir], check=True)
            print(f"Файл успешно конвертирован: {output_path}")
        except subprocess.CalledProcessError as e:
            print(f"Ошибка при конвертации: {e}")
            raise RuntimeError(f"Failed to convert file to {self._output_format}") from e
        finally:
            self._delete_tmp_dir()
        return output_path

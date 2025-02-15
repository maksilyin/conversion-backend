from abc import ABC
import subprocess
import os
from wand.image import Image as WandImage

from classes.ConverterBase import ConverterBase
from collection.vector_service_formats import uniconvertor_input, uniconvertor_output, libreoffice_output, \
    inkscape_formats
from libs.vector import uniconvertor


class VectorConverter(ConverterBase, ABC):
    def convert(self):
        extension = os.path.splitext(self._file_path)[1].lstrip('.').upper()
        output_path = self._get_output_path()
        input_file = self._file_path

        try:
            if extension == 'EPS':
                self._create_tmp_dir()
                input_file_tmp = f"{self._tmp_path}/{os.path.splitext(os.path.basename(self._file_path))[0]}.svg"
                self.libreoffice_convert(self._file_path, input_file_tmp, 'svg')
                input_file = input_file_tmp
                extension = 'SVG'

            if self._output_format.upper() in uniconvertor_output:
                if extension in uniconvertor_input:
                    path = uniconvertor.convert(input_file, output_path)
                    if not path:
                        raise ValueError("Conversion failed")
                    return path

            if self._output_format.upper() in libreoffice_output:
                return self.libreoffice_convert(input_file, output_path, self._output_format)
            elif self._output_format.upper() in inkscape_formats:
                return self.inkscape_convert(input_file, output_path, self._output_format)
            else:
                return self.wand_convert(input_file, output_path, self._output_format)
        except Exception as e:
            raise Exception(e)
        finally:
            self._delete_tmp_dir()

    def inkscape_convert(self, input_file, output_file, output_format):
        command = [
            "inkscape",
            input_file,
            f"--export-type={output_format}",
            f"--export-filename={output_file}"
        ]

        subprocess.run(command, check=True)
        return output_file

    def libreoffice_convert(self, input_file, output_file, output_format):
        output_dir = os.path.dirname(output_file)

        command = [
            "libreoffice",
            "--headless",
            "--convert-to",
            output_format,
            input_file,
            "--outdir",
            output_dir
        ]

        subprocess.run(command, check=True)
        return output_file

    def pstoedit_convert(self, input_file, output_file, output_format):
        extension = os.path.splitext(input_file)[1].lstrip('.').lower()
        try:
            if extension != 'eps':
                self._create_tmp_dir()
                input_file_tmp = f"{self._tmp_path}/{os.path.splitext(os.path.basename(self._file_path))[0]}.eps"
                self.wand_convert(input_file, input_file_tmp, 'eps')
                input_file = input_file_tmp

            command = [
                "pstoedit",
                "-f",
                output_format,
                input_file,
                output_file
            ]

            subprocess.run(command, check=True)
        except Exception as e:
            print(f"pstoedit_convert: {e}")
        finally:
            self._delete_tmp_dir()
        return output_file

    def wand_convert(self, file_path, output_path, output_format):
        with WandImage(filename=file_path) as img:
            img.format = output_format
            img.save(filename=output_path)
        return output_path

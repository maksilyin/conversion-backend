import subprocess
from abc import ABC
from classes.ConverterBase import ConverterBase
from libs.ssconvert import ssconvert_convert


class SpreadsheetsConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        try:
            output_path = ssconvert_convert(self._file_path, output_path)
        except subprocess.CalledProcessError as e:
            raise RuntimeError(f"Failed to convert file to {self._output_format}") from e
        return output_path

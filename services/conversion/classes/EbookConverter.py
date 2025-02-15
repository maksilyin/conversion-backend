from abc import ABC
from libs.calibre_converter import calibre
from classes.ConverterBase import ConverterBase


class EbookConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        output = calibre(self._file_path, output_path)
        return output

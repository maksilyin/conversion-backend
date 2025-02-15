from abc import ABC
import subprocess
from fontTools.ttLib import TTFont
import os

from classes.ConverterBase import ConverterBase

class FontConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        subprocess.run(["fontforge", "-c", f"open('{self._file_path}').generate('{output_path}')"])
        return output_path

    def print_font_info(self, font_path):
        font = TTFont(font_path)
        print(f"Таблицы шрифта ({font_path}): {font.keys()}")
        name_records = font['name'].names
        for record in name_records:
            print(f"{record.nameID}: {record.string.decode(record.getEncoding())}")

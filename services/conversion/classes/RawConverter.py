from abc import ABC

import rawpy
from PIL import Image
import os

from classes.ConverterBase import ConverterBase

class RawConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        with rawpy.imread(self._file_path) as raw:
            print(raw)
            rgb = raw.postprocess()
            img = Image.fromarray(rgb)
            img.save(output_path, format=self._output_format.upper())
        return output_path

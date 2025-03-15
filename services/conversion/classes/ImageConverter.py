import os
from abc import ABC
from wand.image import Image as WandImage
from collection.FileFormats import raw_formats
import rawpy
from PIL import Image

from classes.ConverterBase import ConverterBase


class ImageConverter(ConverterBase, ABC):

    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = os.path.splitext(file_path)[1].lstrip('.').lower()

        try:
            if extension in raw_formats:
                file_path = self._prepare_tiff()

            self.convert_image(file_path, output_path)
        except Exception as e:
            raise Exception(e)
        finally:
            self._cleanup_tmp()

        return output_path

    def convert_image(self, file_path, output_path):
        with WandImage(filename=file_path) as img:
            self.set_params(img)
            img.format = self._output_format
            img.save(filename=output_path)

    def can_convert(self) -> bool:
        return True

    def _prepare_tiff(self):
        base_name = os.path.splitext(os.path.basename(self._file_path))[0]
        tiff_path = f"/tmp/{base_name}.tif"
        self._tmp_path = tiff_path

        with rawpy.imread(self._file_path) as raw:
            rgb = raw.postprocess()
            img = Image.fromarray(rgb)
            img.save(tiff_path, format="TIFF")

        return tiff_path

    def set_params(self, img: WandImage) -> WandImage:
        if self._output_format == 'ico' and (img.width > 256 or img.height > 256):
            img.resize(256, 256)
        img.compression_quality = 75
        if (self._output_format == 'webp'):
            img.compression_quality = 50
            img.options['webp:lossless'] = 'false'
            img.options['webp:auto-filter'] = 'true'
            img.options['webp:method'] = '6'
            img.options['webp:exact'] = 'true'


        return img

    def _cleanup_tmp(self):
        if self._tmp_path and os.path.exists(self._tmp_path):
            try:
                os.remove(self._tmp_path)
            except Exception as e:
                print(f"Ошибка при удалении временного файла: {e}")
import shutil
from abc import ABC
from pyunpack import Archive
import os

from classes.ConverterBase import ConverterBase
from libs.archives import create_new_archive


class ArchiveConverter(ConverterBase, ABC):
    def convert(self) -> str:
        file_path = self._file_path
        is_rename_tar = False
        if file_path.endswith(".bz2") or file_path.endswith(".xz"):
            filename = os.path.splitext(os.path.basename(file_path))[0]
            extension = os.path.splitext(os.path.basename(file_path))[1]
            if not file_path.endswith(f".tar.{extension}"):
                is_rename_tar = True
                file_path = file_path.replace(f"{filename}{extension}", f"{filename}.tar{extension}")
                os.rename(self._file_path, file_path)

        output_path = self._get_output_path()

        if (file_path.endswith(".bz2") or file_path.endswith(".xz")) and output_path.endswith(f".tar.{self._output_format}"):
            output_path = output_path.replace(".tar.", ".")

        if (self._output_format == "bz2"):
            base, _ = os.path.splitext(output_path)
            output_path = base + '.tar.bz2'


        self._create_tmp_dir()
        try:
            try:
                Archive(file_path).extractall(self._tmp_path)
            except Exception as e:
                raise Exception(f"Failed to extract archive '{file_path}': {e}")

            try:
                create_new_archive(self._tmp_path, output_path)
            except Exception as e:
                raise Exception(f"Failed to create new archive '{output_path}': {e}")

        finally:
            if is_rename_tar:
                os.rename(file_path, self._file_path)
            #self._delete_tmp_dir()

        return output_path

    def _create_tmp_dir(self):
        base_name = os.path.splitext(os.path.basename(self._file_path))[0]
        self._tmp_path = f"/tmp/{base_name}"

        if not os.path.exists(self._tmp_path):
            os.makedirs(self._tmp_path)

    def _delete_tmp_dir(self):
        if os.path.exists(self._tmp_path):
            shutil.rmtree(self._tmp_path)


import os
import shutil
import tempfile
from pathlib import Path

import magic
from abc import ABC, abstractmethod


class ConverterBase(ABC):
    _subfolder = "result"

    def __init__(self, file_path: str, output_format=None, output_path=None):
        if output_format is None and output_path is None:
            raise ValueError("Either 'output_format' or 'output_path' must be specified.")

        self._file_path = file_path
        self._tmp_path = None
        self._tmp_created = False
        self._output_path = None
        self._output_dir = None

        if not output_path is None:
            self._output_path = output_path
            self._output_format = os.path.splitext(output_path)[1][1:].lower()
        else:
            self._output_format = output_format.lower()

        mime = magic.Magic(mime=True)
        self._mime_type = mime.from_file(file_path)

    def _get_output_path(self) -> str:
        """
            Creates a path for saving the file in the 'result' subfolder.
            """
        if self._output_path is not None:
            output_path = self._output_path
            self._output_dir = output_dir = os.path.dirname(output_path)

            if not os.path.exists(output_dir):
                os.makedirs(output_dir)

            return output_path

        self._output_dir = result_dir = os.path.join(os.path.dirname(self._file_path), self._subfolder)

        if not os.path.exists(result_dir):
            os.makedirs(result_dir)

        base_name = os.path.splitext(os.path.basename(self._file_path))[0]
        return os.path.join(result_dir, f"{base_name}.{self._output_format}")

    @abstractmethod
    def convert(self) -> str:
        pass

    def _delete_tmp_dir(self):
        if self._tmp_created and os.path.exists(self._tmp_path):
            shutil.rmtree(self._tmp_path)

    def _create_tmp_dir(self, build_path=None):
        self._tmp_path = tempfile.mkdtemp(dir="/app/storage/tmp")
        self._tmp_created = True

        if build_path is not None:
            return str(Path(self._tmp_path) / build_path)

        return self._tmp_path

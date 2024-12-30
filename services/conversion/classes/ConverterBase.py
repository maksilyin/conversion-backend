import os
import magic
from abc import ABC, abstractmethod
from collection.FileTypes import fileTypes


class ConverterBase(ABC):
    _subfolder = "result"

    def __init__(self, file_path: str, output_format: str):
        self._file_path = file_path
        self._output_format = output_format.lower()

        mime = magic.Magic(mime=True)
        self._mime_type = mime.from_file(file_path)

    def _get_output_path(self) -> str:
        """
            Creates a path for saving the file in the 'result' subfolder.
            """
        # Define the result directory
        result_dir = os.path.join(os.path.dirname(self._file_path), self._subfolder)

        # Create the result directory if it doesn't exist
        if not os.path.exists(result_dir):
            os.makedirs(result_dir)

        # Generate the output file path
        base_name = os.path.splitext(os.path.basename(self._file_path))[0]
        return os.path.join(result_dir, f"{base_name}.{self._output_format}")

    @abstractmethod
    def convert(self) -> str:
        pass

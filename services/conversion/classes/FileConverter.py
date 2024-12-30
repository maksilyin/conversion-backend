import magic
import os

from classes import ConverterBase
from classes.ImageConverter import ImageConverter
from classes.AudioConverter import AudioConverter
from classes.PdfConverter import PdfConverter
from classes.DocumentConverter import DocumentConverter
from collection.FileTypes import fileTypes


class FileConverter:
    def __init__(self, file_type: str, file_path: str, output_format: str):
        self.__strategy = None
        self.__file_path = file_path
        self.__output_format = output_format.lower()
        self.__file_type = file_type

        mime = magic.Magic(mime=True)
        self.__mime_type = mime.from_file(self.__file_path)

        if not file_type or file_type == 'unknown':
            self.set_file_type()

    def set_strategy(self, strategy: ConverterBase):
        self.__strategy = strategy(self.__file_path, self.__output_format)

    def convert(self) -> dict:
        """
        Converts a file to the specified format.
        Returns a dictionary with keys:
        - 'status': bool (whether the conversion was successful)
        - 'file_name': str (name of the output file, if successful)
        - 'error': str (error message, if an error occurred)
        """
        try:
            # Check if the file exists
            if not os.path.exists(self.__file_path):
                return self.get_error("File not found.")

            # Determine the conversion strategy
            strategy_class = self.determine_strategy()

            if not strategy_class:
                source_extension = os.path.splitext(self.__file_path)[1].lstrip('.').lower() or "unknown"
                return self.get_error(
                    f"Conversion from {source_extension} to {self.__output_format} is not supported."
                )

            # Set the conversion strategy and perform the conversion
            self.set_strategy(strategy_class)
            output_path = self.__strategy.convert()

            return {
                "status": True,
                "filename": os.path.basename(output_path),
                "output": output_path,
                "error": None,
                "extension": self.__output_format,
            }

        except Exception as e:
            return self.get_error(f"Error conversion {self.__file_path} to {self.__output_format}: {str(e)}")

    def get_error(self, message: str) -> dict:
        """
        Returns a structured error dictionary.
        """
        return {
            "status": False,
            "error": message,
            "extension": f"{self.__output_format} {self.__mime_type}"
        }

    def set_file_type(self):
        if not self.__mime_type:
            return

        for category, mime_list in fileTypes.items():
            if self.__mime_type in mime_list:
                self.__file_type = category
                return

    def determine_strategy(self):
        try:
            mime_type = self.__mime_type
            file_type = self.__file_type

            match mime_type:
                case "application/pdf":
                    return PdfConverter

            match file_type:
                case "raw" | "image":
                    return ImageConverter
                case "audio":
                    return AudioConverter
                case "document":
                    return DocumentConverter
                case _:
                    return None
        except Exception as e:
            raise Exception(f"Error: {str(e)}")

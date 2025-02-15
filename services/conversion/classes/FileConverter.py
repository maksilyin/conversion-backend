import magic
import os

from classes import ConverterBase
from classes.ImageConverter import ImageConverter
from classes.VectorConverter import VectorConverter
from classes.AudioConverter import AudioConverter
from classes.VideoConverter import VideoConverter
from classes.PdfConverter import PdfConverter
from classes.EbookConverter import EbookConverter
from classes.DocumentConverter import DocumentConverter
from classes.FontConverter import FontConverter
from classes.ArchiveConverter import ArchiveConverter
from classes.SpreadsheetsConverter import SpreadsheetsConverter
from collection.FileTypes import fileTypes
from libs.Converter import convert_file
from libs.helpers import get_file_type_by_format


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

    def convert(self):
        if not os.path.exists(self.__file_path):
            raise FileNotFoundError("File not found.")

        strategy = self.determine_strategy()

        output_path = convert_file(strategy=strategy, input_file=self.__file_path, output_format=self.__output_format)
        """
        # Determine the conversion strategy
        strategy_class = self.determine_strategy()

        if not strategy_class:
            source_extension = os.path.splitext(self.__file_path)[1].lstrip('.').lower() or "unknown"
            raise NotImplementedError(f"Conversion from {source_extension} to {self.__output_format} is not supported.")

        # Set the conversion strategy and perform the conversion
        self.set_strategy(strategy_class)
        output_path = self.__strategy.convert()
        """

        if output_path == -1:
            return None

        return {
            "status": True,
            "filename": os.path.basename(output_path),
            "output": output_path,
            "error": None,
            "extension": self.__output_format,
        }

    def set_file_type(self):
        if not self.__mime_type:
            return

        for category, mime_list in fileTypes.items():
            if self.__mime_type in mime_list:
                self.__file_type = category
                return

    def determine_strategy(self):
        mime_type = self.__mime_type
        file_type = self.__file_type
        file_type_output = get_file_type_by_format(self.__output_format)

        match mime_type:
            case "application/pdf":
                return 'pdf'

        if file_type == "vector" and "image" in file_type_output:
            return "image"

        if file_type == "image" and "vector" in file_type_output:
            return "vector"

        return str(file_type)

        match file_type:
            case "raw" | "image":
                return ImageConverter
            case "vector":
                return VectorConverter
            case "audio":
                return AudioConverter
            case "video":
                return VideoConverter
            case "ebook":
                return EbookConverter
            case "document":
                return DocumentConverter
            case "font":
                return FontConverter
            case "archive":
                return ArchiveConverter
            case "spreadsheet":
                return SpreadsheetsConverter
            case _:
                return None

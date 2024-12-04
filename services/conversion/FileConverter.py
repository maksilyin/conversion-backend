import mimetypes
from PIL import Image
from wand.image import Image as WandImage
import os


class FileConverter:
    def __init__(self, file_path: str, output_format: str):
        self.file_path = file_path
        self.output_format = output_format.lower()

    def convert(self) -> dict:
        """
        Converts a file to the specified format.
        Returns a dictionary with keys:
        - 'success': bool (whether the conversion was successful)
        - 'file_name': str (name of the output file, if successful)
        - 'error': str (error message, if an error occurred)
        """
        try:
            # Check if the file exists
            if not os.path.exists(self.file_path):
                return {"status": False, "error": "File not found."}

            # Determine MIME type
            mime_type, _ = mimetypes.guess_type(self.file_path)
            if not mime_type:
                return {"status": False, "error": "Could not determine the file's MIME type."}

            # Select the appropriate method for conversion
            if mime_type.startswith('image/'):
                file_name = self._convert_image()
            elif mime_type == 'application/pdf':
                file_name = self._convert_pdf()
            else:
                return {"status": False, "error": f"File type {mime_type} is not supported."}

            return {"status": True, "filename": file_name, "error": None}

        except Exception as e:
            return {"status": False, "error": str(e)}

    def _convert_image(self) -> str:
        """
        Converts an image using Wand.
        Returns the name of the output file.
        """
        with WandImage(filename=self.file_path) as img:
            img.format = self.output_format
            output_path = self._get_output_path()
            img.save(filename=output_path)
            return os.path.basename(output_path)

    def _convert_pdf(self) -> str:
        """
        Converts a PDF to an image using Wand.
        Returns the name of the output file.
        """
        with WandImage(filename=self.file_path) as pdf:
            pdf.format = self.output_format
            output_path = self._get_output_path()
            pdf.save(filename=output_path)
            return os.path.basename(output_path)

    def _get_output_path(self) -> str:
        """
        Creates a path for saving the file based on the output format.
        """
        base, _ = os.path.splitext(self.file_path)
        return f"{base}_converted.{self.output_format}"
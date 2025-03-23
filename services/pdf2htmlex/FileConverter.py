import os
import subprocess


class FileConverter:
    _subfolder = "result"

    def __init__(self, file_path, output_format=None, output_path=None):
        self._file_path = file_path
        self._output_format = output_format
        self._output_path = output_path

        if output_format is None and self._output_path is not None:
            self._output_format = os.path.splitext(os.path.basename(self._file_path))[1].lower()

    def _get_output_path(self):
        if self._output_path is not None:
            if not os.path.exists(self._output_path):
                os.makedirs(self._output_path)
            return self._output_path

    def convert(self):
        print("Input PDF exists:", os.path.exists(self._file_path), flush=True)
        print("Input PDF path:", self._file_path, flush=True)
        output_path = self._get_output_path()
        print("output_path:", output_path, flush=True)
        command = [
            "pdf2htmlEX",
            "--decompose-ligature",
            "1",
            "--process-outline", 
            "0",
            #"--embed",
            #"cfij",
            "--css-filename",
            "styles.css",
            "--dest-dir",
            output_path,
            "--bg-format",
            "png",
            self._file_path
        ]
        try:
            subprocess.check_call(command)
            
            print("subprocess", {
                "status": True,
                "filename": os.path.basename(output_path),
                "output": output_path,
                "error": None,
                "extension": self._output_format,
            }, flush=True)

            return {
                "status": True,
                "filename": os.path.basename(output_path),
                "output": output_path,
                "error": None,
                "extension": self._output_format,
            }
        except Exception as e:
            print("error", e, flush=True)
            return self.get_error("Error conversion {} to {}: {}".format(self._file_path, self._output_format, str(e)))

    def get_error(self, message):
        return {
            "status": False,
            "error": message,
            "extension": self._output_format
        }

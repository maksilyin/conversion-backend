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
            return self._output_path

        result_dir = os.path.join(os.path.dirname(self._file_path), self._subfolder)

        if not os.path.exists(result_dir):
            os.makedirs(result_dir)

        base_name = os.path.splitext(os.path.basename(self._file_path))[0]
        return os.path.join(result_dir, "{}.{}".format(base_name, self._output_format))

    def convert(self):
        output_path = self._get_output_path()
        print output_path
        command = ["uniconvertor", self._file_path, output_path]
        try:
            subprocess.check_call(command)

            return {
                "status": True,
                "filename": os.path.basename(output_path),
                "output": output_path,
                "error": None,
                "extension": self._output_format,
            }
        except Exception as e:
            return self.get_error("Error conversion {} to {}: {}".format(self._file_path, self._output_format, str(e)))

    def get_error(self, message):
        return {
            "status": False,
            "error": message,
            "extension": self._output_format
        }

import os
import subprocess


def libreoffice(file_path, output_path, extension):
    output_dir = os.path.abspath(os.path.dirname(output_path))

    command = [
        "libreoffice",
        "--headless",
        "--convert-to",
        extension,
        file_path,
        "--outdir",
        output_dir
    ]

    subprocess.run(command, check=True)
    return output_path

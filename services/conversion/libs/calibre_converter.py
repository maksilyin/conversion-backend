import subprocess


def calibre(file_path, output_path):
    command = [
        "ebook-convert",
        file_path,
        output_path
    ]

    subprocess.run(command)
    return output_path

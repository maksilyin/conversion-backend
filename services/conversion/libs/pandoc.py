import subprocess


def pandoc(file_path, output_path):
    command = [
        "pandoc",
        file_path,
        "-o",
        output_path
    ]

    subprocess.run(command)
    return output_path

import subprocess


def wkhtmltopdf(file_path, output_path):
    command = [
        "wkhtmltopdf",
        file_path,
        output_path
    ]

    subprocess.run(command)
    return output_path

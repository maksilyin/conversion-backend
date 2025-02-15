import subprocess


def ssconvert_convert(file_path, output_path):
    subprocess.run(
        ["ssconvert", file_path, output_path],
        check=True
    )

    return output_path
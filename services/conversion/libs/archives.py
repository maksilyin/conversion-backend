import os
from pathlib import Path

import patoolib


def create_new_archive(dir, output_path):
    files_to_add = []
    output_path = str(Path(output_path).resolve())
    for root, _, files in os.walk(dir):
        for file in files:
            full_path = os.path.join(root, file)
            relative_path = os.path.relpath(full_path, dir)
            files_to_add.append(relative_path)

    if not files_to_add:
        raise Exception("The list of files to archive is empty")

    current_dir = os.getcwd()
    try:
        os.chdir(dir)
        patoolib.create_archive(output_path, files_to_add)
    except Exception:
        raise Exception(f"create_new_archive:")
    finally:
        os.chdir(current_dir)

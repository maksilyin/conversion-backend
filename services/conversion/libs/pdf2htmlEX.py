import requests


def pdf2htmlEX_convert(file_path, output_path):
    data = {
        'file_path': file_path,
        'output_path': output_path
    }
    response = requests.post('http://pdf2htmlex:5001/convert', json=data)
    if response.status_code == 200:
        return response.json()['output']
    else:
        return False

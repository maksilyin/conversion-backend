from FileConverter import FileConverter
from flask import Flask, request, jsonify

app = Flask(__name__)

@app.route('/convert', methods=['POST'])
def convert():
    data = request.json
    if not data:
        return jsonify({'error': 'No data provided'}), 400

    file_path = data.get('file_path')
    output_path = data.get('output_path')

    if not file_path or not output_path:
        return jsonify({'error': 'Missing required parameters'}), 400

    converter = FileConverter(file_path, output_path=output_path)
    result = converter.convert()

    if result['status'] == True:
        return jsonify(result)
    else:
        return jsonify(result), 500


app.run(host='0.0.0.0', port=5001)
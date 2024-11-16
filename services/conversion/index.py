import pika
import json
from PIL import Image
import os


def convert_image(file_path, target_format):
    target_format = target_format.lower().lstrip('.')

    try:
        img = Image.open(file_path)
        base, _ = os.path.splitext(file_path)
        new_file_path = f"{base}.{target_format}"

        img.save(new_file_path)

        filename = os.path.basename(new_file_path)
        return filename
    except Exception as e:
        print(f"Error converting file {file_path} to {target_format}: {e}")
        return False


def send_response(task, hash, filename, status, payload):
    print('send_response')
    connection = pika.BlockingConnection(pika.ConnectionParameters('rabbitmq'))
    channel = connection.channel()
    channel.queue_declare(queue='service_messages')

    response_data = {
        'task': task,
        'hash': hash,
        'filename': filename,
        'status': status,
        'service': 'convert',
        'type': 'file',
        'index': payload['index'],
        'total': payload['total'],
    }
    channel.basic_publish(
        exchange='',
        routing_key='service_messages',
        body=json.dumps(response_data)
    )
    connection.close()


def process_conversion_task(task_data):
    if not all(key in task_data for key in ['task_id', 'file_path', 'target_format', 'hash']):
        print("Error: Missing required keys in task_data:", task_data)
        return
    file_path = task_data['file_path']
    target_format = task_data['target_format']

    filename = convert_image(file_path, target_format)
    status = bool(filename)
    send_response(task_data['task_id'], task_data['hash'], filename, status, task_data)


connection = pika.BlockingConnection(pika.ConnectionParameters('rabbitmq'))
channel = connection.channel()
channel.queue_declare(queue='convert')


def callback(ch, method, properties, body):
    task_data = json.loads(body)
    process_conversion_task(task_data)
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_consume(queue='convert', on_message_callback=callback)
print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()

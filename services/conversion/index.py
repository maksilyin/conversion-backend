import os
import pika
import json
from classes.FileConverter import FileConverter


def send_response(task, hash, result, payload):
    print('send_response')
    connection = pika.BlockingConnection(pika.ConnectionParameters('rabbitmq'))
    channel = connection.channel()
    channel.queue_declare(queue='service_messages')

    response_data = {
        'task': task,
        'hash': hash,
        'result': result,
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


def get_error(message: str, output_format) -> dict:
    return {
        "status": False,
        "error": message,
        "extension": output_format
    }


def process_conversion_task(task_data):
    try:
        if not all(key in task_data for key in ['task_id', 'file_path', 'file_type', 'output_format', 'hash']):
            ValueError("Error: Missing required keys in task_data")

        if not task_data['file_path'] or not task_data['output_format']:
            raise ValueError("Error: Output format and file path are not specified.")

        file_path = task_data['file_path']
        output_format = task_data['output_format']
        file_type = task_data['file_type']

        converter = FileConverter(file_type, file_path, output_format)
        result = converter.convert()
    except FileNotFoundError as e:
        result = get_error(f"File error: {e}", task_data['output_format'])
    except ValueError as e:
        result = get_error(f"Value error: {e}", task_data['output_format'])
    except Exception as e:
        result = get_error(f"Unexpected error: {e}", task_data['output_format'])

    if result is None:
        return

    send_response(task_data['task_id'], task_data['hash'], result, task_data)


RABBITMQ_USER = os.getenv("RABBITMQ_USER", "guest")
RABBITMQ_PASSWORD = os.getenv("RABBITMQ_PASSWORD", "guest")

credentials = pika.PlainCredentials(RABBITMQ_USER, RABBITMQ_PASSWORD)
parameters = pika.ConnectionParameters(host='rabbitmq', credentials=credentials)

connection = pika.BlockingConnection(parameters)
channel = connection.channel()
channel.queue_declare(queue='convert', durable=True)


def callback(ch, method, properties, body):
    task_data = json.loads(body)
    process_conversion_task(task_data)
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_consume(queue='convert', on_message_callback=callback)
print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()

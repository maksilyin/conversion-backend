import pika
import json
from PIL import Image
import os
from FileConverter import FileConverter


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


def process_conversion_task(task_data):
    if not all(key in task_data for key in ['task_id', 'file_path', 'output_format', 'hash']):
        print("Error: Missing required keys in task_data:", task_data)
        return
    file_path = task_data['file_path']
    output_format = task_data['output_format']

    converter = FileConverter(file_path, output_format)
    result = converter.convert()

    #filename = convert_image(file_path, output_format)

    send_response(task_data['task_id'], task_data['hash'], result, task_data)


connection = pika.BlockingConnection(pika.ConnectionParameters('rabbitmq'))
channel = connection.channel()
channel.queue_declare(queue='convert', durable=True)


def callback(ch, method, properties, body):
    task_data = json.loads(body)
    process_conversion_task(task_data)
    ch.basic_ack(delivery_tag=method.delivery_tag)


channel.basic_consume(queue='convert', on_message_callback=callback)
print(' [*] Waiting for messages. To exit press CTRL+C')
channel.start_consuming()

# -*- coding: utf-8 -*-
from kombu import Connection, Exchange, Queue, Consumer, Producer
import json
from FileConverter import FileConverter

RABBIT_URL = 'amqp://guest:guest@rabbitmq:5672//'

connection = Connection(RABBIT_URL)
exchange = Exchange('service_exchange', type='direct', durable=True)
queue_uniconvertor = Queue('uniconvertor', exchange, routing_key='uniconvertor', durable=True)
queue_service_messages = Queue('service_messages', exchange, routing_key='service_messages', durable=False)


def send_response(task, hash, result, payload):
    print('send_response')
    with connection.Producer() as producer:
        response_data = {
            'task': task,
            'hash': hash,
            'result': result,
            'service': 'convert',
            'type': 'file',
            'index': payload['index'],
            'total': payload['total'],
        }
        producer.publish(
            json.dumps(response_data),
            exchange=exchange,
            routing_key='service_messages',
            declare=[queue_service_messages]
        )


def process_conversion_task(task_data):
    required_keys = ['task_id', 'file_path', 'file_type', 'output_format', 'hash']

    if not all(key in task_data for key in required_keys):
        print("Error: Missing required keys in task_data:", task_data)
        return

    file_path = task_data['file_path']
    output_format = task_data['output_format']

    converter = FileConverter(file_path, output_format)
    result = converter.convert()

    send_response(task_data['task_id'], task_data['hash'], result, task_data)


def handle_message(body, message):
    task_data = json.loads(body)
    process_conversion_task(task_data)
    message.ack()


# Запуск потребителя
with connection.Consumer(queues=queue_uniconvertor, callbacks=[handle_message]) as consumer:
    print(' [*] Waiting for messages. To exit press CTRL+C')
    while True:
        try:
            connection.drain_events()
        except KeyboardInterrupt:
            print("Exiting...")
            break

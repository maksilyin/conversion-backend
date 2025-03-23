FROM pdf2htmlex/pdf2htmlex:0.18.8.rc2-master-20200820-ubuntu-20.04-x86_64

WORKDIR /app

RUN apt-get update && apt-get install -y python3 python3-pip

COPY requirements.txt ./
RUN pip3 install --no-cache-dir -r requirements.txt

COPY . .

USER www-data

ENTRYPOINT []

CMD ["python3", "index.py"]
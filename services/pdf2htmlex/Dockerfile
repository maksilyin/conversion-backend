FROM ubuntu:20.04

ENV DEBIAN_FRONTEND=noninteractive

RUN apt-get update && apt-get install -y \
    sudo git cmake g++ pkg-config make wget curl unzip \
    build-essential file python3 python3-pip \
    libfontforge-dev libpoppler-dev libpng-dev libjpeg-dev \
    libcairo2-dev libfreetype6-dev fontforge \
    && rm -rf /var/lib/apt/lists/*

WORKDIR /opt
RUN git clone https://github.com/pdf2htmlEX/pdf2htmlEX.git && \
    cd pdf2htmlEX && \
    git submodule update --init --recursive

WORKDIR /opt/pdf2htmlEX

RUN ./buildScripts/buildInstallLocallyApt

WORKDIR /app

COPY requirements.txt .
RUN pip3 install --no-cache-dir -r requirements.txt

COPY . .

USER www-data

CMD ["python3", "index.py"]
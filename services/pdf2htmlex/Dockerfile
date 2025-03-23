FROM alpine:3.12

# Устанавливаем рабочую директорию
WORKDIR /app

# Устанавливаем зависимости
RUN apk add --no-cache \
    libstdc++ \
    libgcc \
    poppler-utils \
    poppler \
    poppler-data \
    fontconfig \
    cairo \
    bash \
    curl \
    tar \
    python3 \
    py3-pip \
    py3-setuptools \
    gettext \
    libxml2 \
    glib-dev \
    build-base

# Скачиваем и устанавливаем libiconv
RUN curl -LO https://ftp.gnu.org/pub/gnu/libiconv/libiconv-1.16.tar.gz && \
    tar -xvzf libiconv-1.16.tar.gz && \
    cd libiconv-1.16 && \
    ./configure --prefix=/usr/local && \
    make && \
    make install && \
    cd .. && \
    rm -rf libiconv-1.16 libiconv-1.16.tar.gz

RUN sed -i 's/^xfs:x:33:/www-data:x:33:/' /etc/group && \
    sed -i 's/^xfs:x:33:33:/www-data:x:33:33:/' /etc/passwd

# Копируем архив pdf2htmlEX
COPY pdf2htmlEX-0.18.8.rc1-master-20200630-alpine-3.12.0-x86_64.tar.gz /tmp/pdf2htmlEX.tar.gz

# Распаковываем архив в корневую директорию
RUN tar -xvzf /tmp/pdf2htmlEX.tar.gz -C / && \
    chmod +x /usr/local/bin/pdf2htmlEX

# Копируем файл зависимостей Python
COPY requirements.txt .

# Устанавливаем зависимости Python
RUN pip3 install --no-cache-dir -r requirements.txt

# Копируем файлы приложения
COPY . .

USER www-data

# Указываем команду по умолчанию
CMD ["python3", "index.py"]

FROM python:3.9-slim

WORKDIR /app

# Устанавливаем зависимости и ImageMagick
RUN apt-get update && apt-get install -y \
    imagemagick \
    ghostscript \
    libmagickwand-dev \
    && rm -rf /var/lib/apt/lists/*

COPY requirements.txt .
RUN pip install -r requirements.txt

COPY . .

CMD ["python", "index.py"]
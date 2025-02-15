FROM debian:stretch

WORKDIR /app

RUN sed -i 's/deb.debian.org/archive.debian.org/g' /etc/apt/sources.list && \
    sed -i 's/security.debian.org\/debian-security/archive.debian.org\/debian-security/g' /etc/apt/sources.list && \
    sed -i '/stretch-updates/d' /etc/apt/sources.list && \
    echo 'Acquire::Check-Valid-Until "false";' > /etc/apt/apt.conf.d/99no-check-valid-until && \
    apt-get update && \
    apt-get install -y --no-install-recommends \
    python2.7 \
    python2.7-dev \
    liblcms2-2 \
    libmagickwand-6.q16-3 \
    python-cairo \
    python-pil \
    python-reportlab \
    libpango1.0-0 \
    curl \
    ca-certificates && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# Установка pip для Python 2.7
RUN curl https://bootstrap.pypa.io/pip/2.7/get-pip.py --output get-pip.py && \
    python2.7 get-pip.py && \
    rm get-pip.py
COPY python-uniconvertor-2.0rc5_debian_9_amd64.deb /tmp/python-uniconvertor.deb

RUN apt-get update && \
    dpkg -i /tmp/python-uniconvertor.deb && \
    apt-get -f install -y && \
    rm -f /tmp/python-uniconvertor.deb

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

# Copy application files
COPY . .

# Set the command to run the app
CMD ["python", "index2.py"]

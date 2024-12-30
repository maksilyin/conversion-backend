FROM python:3.13.1-bookworm

WORKDIR /app

RUN apt-get update && apt-get install -y \
    imagemagick \
    ghostscript \
    libmagickwand-dev \
    dcraw \
    libraw-bin \
    autoconf \
    automake \
    build-essential \
    cmake \
    git-core \
    libass-dev \
    libfreetype6-dev \
    libsdl2-dev \
    libtool \
    libva-dev \
    libvdpau-dev \
    libvorbis-dev \
    libxcb1-dev \
    libxcb-shm0-dev \
    libxcb-xfixes0-dev \
    libopus-dev \
    libopencore-amrnb-dev \
    libopencore-amrwb-dev \
    pkg-config \
    texinfo \
    wget \
    yasm \
    zlib1g-dev \
    nasm \
    libreoffice \
    pandoc \
    tesseract-ocr \
    build-essential \
    git \
    poppler-utils \
    xpdf \
    libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

RUN mkdir -p /ffmpeg_sources && cd /ffmpeg_sources && \
    git clone https://code.videolan.org/videolan/x264.git && \
    cd x264 && \
    ./configure --prefix="/ffmpeg_build" --bindir="/usr/local/bin" --enable-static && \
    make -j$(nproc) && \
    make install

RUN mkdir -p /ffmpeg_sources && cd /ffmpeg_sources && \
    git clone https://github.com/videolan/x265.git && \
    cd x265/build/linux && \
    cmake -DCMAKE_INSTALL_PREFIX="/ffmpeg_build" -DENABLE_SHARED=OFF ../../source && \
    make -j$(nproc) && \
    make install

RUN cd /ffmpeg_sources && \
    git clone https://github.com/mstorsjo/fdk-aac.git && \
    cd fdk-aac && \
    autoreconf -fiv && \
    ./configure --prefix="/ffmpeg_build" --disable-shared && \
    make -j$(nproc) && \
    make install

RUN cd /ffmpeg_sources && \
    wget https://downloads.sourceforge.net/project/lame/lame/3.100/lame-3.100.tar.gz && \
    tar xzvf lame-3.100.tar.gz && \
    cd lame-3.100 && \
    ./configure --prefix="/ffmpeg_build" --enable-nasm --disable-shared && \
    make -j$(nproc) && \
    make install

RUN cd /ffmpeg_sources && \
    git clone https://git.ffmpeg.org/ffmpeg.git ffmpeg && \
    cd ffmpeg && \
    PKG_CONFIG_PATH="/ffmpeg_build/lib/pkgconfig" ./configure \
        --prefix="/ffmpeg_build" \
        --pkg-config-flags="--static" \
        --extra-cflags="-I/ffmpeg_build/include" \
        --extra-ldflags="-L/ffmpeg_build/lib" \
        --extra-libs="-lpthread -lm" \
        --bindir="/usr/local/bin" \
        --enable-gpl \
        --enable-version3 \
        --enable-libass \
        --enable-libfdk-aac \
        --enable-libfreetype \
        --enable-libvorbis \
        --enable-libx264 \
        --enable-libx265 \
        --enable-libopus \
        --enable-libopencore-amrnb \
        --enable-libopencore-amrwb \
        --enable-libmp3lame \
        --enable-nonfree && \
    make -j$(nproc) && \
    make install && \
    hash -r && \
    rm -rf /ffmpeg_sources

COPY pdf2htmlEX.deb /tmp/pdf2htmlEX.deb

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY . .

CMD ["python", "index.py"]

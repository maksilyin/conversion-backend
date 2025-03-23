FROM python:3.13.1-bookworm

WORKDIR /app

RUN apt-get update && \
    apt-get install -y software-properties-common && \
    echo "deb http://deb.debian.org/debian bookworm main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb http://security.debian.org/debian-security bookworm-security main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb http://deb.debian.org/debian bookworm-updates main contrib non-free" >> /etc/apt/sources.list && \
    echo "deb http://deb.debian.org/debian buster main contrib non-free" >> /etc/apt/sources.list && \
    apt-get update

RUN apt-get install -y \
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
    zlib1g-dev \
    git \
    yasm \
    wget \
    nasm \
    libxvidcore-dev \
    libvorbis-dev \
    libopus-dev \
    zlib1g-dev \
    libvpx-dev \
    libtheora-dev \
    libaom-dev

RUN apt-get install -y \
    inkscape \
    pstoedit \
    fig2dev \
    hp2xx \
    libwmf-bin \
    libwmf-dev \
    vlc \
    genisoimage \
    lcab \
    jlha-utils \
    arj \
    cpio \
    rar \
    unrar \
    imagemagick \
    ghostscript \
    libmagickwand-dev \
    dcraw \
    libraw-bin \
    autoconf \
    automake \
    build-essential

RUN apt-get install -y \
    calibre \
    pkg-config \
    texinfo \
    libreoffice \
    tesseract-ocr \
    poppler-utils \
    xpdf \
    libjpeg-dev \
    fontforge \
    python3-fontforge \
    p7zip \
    gnumeric \
    wkhtmltopdf \
    pandoc \
    passwd \
    unar && \
    apt-get clean && \
    rm -rf /var/lib/apt/lists/*

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
        --enable-libxvid \
        --enable-libvpx \
        --enable-libaom \
        --enable-libtheora \
        --enable-nonfree && \
    make -j$(nproc) && \
    make install && \
    hash -r && \
    rm -rf /ffmpeg_sources

COPY requirements.txt .
RUN pip install --no-cache-dir -r requirements.txt

COPY policy.xml /etc/ImageMagick-6/policy.xml

COPY . .
USER www-data

CMD ["python", "index.py"]

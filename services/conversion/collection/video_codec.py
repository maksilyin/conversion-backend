video_codec = {
    # Common Video Formats
    "mp4": {"codec": "libx264", "f": "mp4", "fps": "30", "scale": "-1:720"},  # MP4 (H.264)
    "avi": {"codec": "libxvid", "f": "avi", "fps": "25"},                    # AVI (Xvid)
    #"mkv": {"codec": "libx265", "bitrate": "800k", "fps": "24"},               # MKV (H.265)
    "mov": {"codec": "prores", "f": "mov", "fps": "30"},                    # MOV (ProRes)
    "wmv": {"codec": "wmv2", "f": "asf", "fps": "30"},                      # WMV
    "flv": {"codec": "flv","f": "flv", "fps": "25"},                       # FLV
    "divx": {"codec": "libxvid", "f": "avi", "fps": "30"},
    "xvid": {"codec": "libxvid", "f": "avi", "fps": "30"},
    "rmvb": {"bitrate": "500k", "codec": "rv10", "f": "rm", "vf": 'scale=1280:720', "rtbufsize": "64k", "bufsize": "500k", "maxrate": "800k"},
    "mpeg2": {"f": "mpeg"},
    "avchd": {"f": "mpegts"},
    "rm": {"bitrate": "500k", "codec": "rv10", "vf": "scale=1280:720", "rtbufsize": "64k", "bufsize": "500k", "maxrate": "800k"},
    "mxf": {"ar": "48000"},
    "av1": {"codec": "libaom-av1", "f": "mp4", "fps": "30"},

    # Lossless Formats
    "mjpeg": {"codec": "mjpeg", "bitrate": None, "f": "avi", "fps": "30"},                     # MJPEG
    "yuv": {"codec": None, "bitrate": None, "f": "rawvideo", "fps": "30"},                    # Raw YUV
    "huffyuv": {"codec": "huffyuv", "bitrate": None, "f": "avi", "fps": "30"},                # HuffYUV

    # Web and Streaming Formats
    "webm": {"codec": "libvpx", "f": "webm", "fps": "30"},                 # WebM (VP8)
    "vp9": {"codec": "libvpx-vp9", "f": "webm", "fps": "30"},             # WebM (VP9)
    "ogv": {"codec": "libtheora", "f": "ogg", "fps": "25"},                # OGV (Theora)

    # Mobile Formats
    "3gp": {"codec": "libx264", "f": "3gp", "fps": "25", "scale": "-1:480", "ar": "8000", "ac": "1"}, # 3GP
    "3g2": {"codec": "libx264", "f": "3g2", "fps": "25", "scale": "-1:480", "ar": "8000", "ac": "1"}, # 3G2

    # Experimental and Rarely Used Formats
    "hevc": {"codec": "libx265", "f": "hevc", "fps": "30"},                # HEVC (H.265)
    "dv": {"codec": "dvvideo", "f": "dv", "fps": "25"},                      # DV
    "mpeg": {"codec": "mpeg2video", "f": "mpeg", "fps": "25"},            # MPEG-2
    "vob": {"codec": "mpeg2video", "f": "vob", "fps": "25"},              # VOB (DVD Video)
}
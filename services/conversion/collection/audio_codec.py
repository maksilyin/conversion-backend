audio_codec = {
    # Lossy Audio Formats
    "mp3": {"codec": "libmp3lame", "bitrate": "192k"},       # MP3
    "m4a": {"codec": "aac", "bitrate": "192k"},              # M4A
    "ogg": {"codec": "libvorbis", "bitrate": "128k", "ar": "44100"},        # OGG
    "vorbis": {"codec": "libvorbis", "bitrate": "128k", "f": "ogg", "ar": "44100"},     # vorbis
    "opus": {"codec": "libopus", "bitrate": "128k"},         # Opus
    "amr": {"codec": "libopencore_amrnb", "bitrate": "12.2k", "ar": "8000", "ac": "1"}, # AMR (Narrowband)
    "wma": {"codec": "wmav2", "bitrate": "128k"},            # WMA

    # Lossless Audio Formats
    "wav": {"codec": None, "bitrate": None},                # WAV
    "flac": {"codec": "flac", "bitrate": None},             # FLAC
    "alac": {"codec": "alac", "bitrate": None},             # ALAC (Apple Lossless)
    "ape": {"codec": "ape", "bitrate": None},               # Monkey's Audio
    "wv": {"codec": "wavpack", "bitrate": None},            # WavPack

    # Specialized Audio Formats
    "caf": {"codec": None, "bitrate": None},                # CAF (Core Audio Format)
    "aiff": {"codec": None, "bitrate": None},               # AIFF
    "au": {"codec": None, "bitrate": None},                 # AU (Sun Microsystems)
    "snd": {"codec": None, "bitrate": None},                # SND

    # MIDI and Other Formats
    "midi": {"codec": None, "bitrate": None},               # MIDI
    "mid": {"codec": None, "bitrate": None},                # MIDI (alternative)
    "rmi": {"codec": None, "bitrate": None},                # RIFF MIDI

    # Broadcast and Telephony Formats
    "gsm": {"codec": "libgsm", "bitrate": None},            # GSM
    "dts": {"codec": "dca", "bitrate": "768k"},             # DTS
    "ac3": {"codec": "ac3", "bitrate": "384k"},             # AC-3
    "eac3": {"codec": "eac3", "bitrate": "640k"},           # E-AC-3

    # Streaming Formats
    "m3u": {"codec": None, "bitrate": None},                # M3U Playlist
    "pls": {"codec": None, "bitrate": None},                # PLS Playlist

    # Experimental / Rarely Used Formats
    "adx": {"codec": "adx", "bitrate": None},               # CRI ADX
    "tta": {"codec": "tta", "bitrate": None},               # True Audio
    "shn": {"codec": None, "bitrate": None},                # Shorten
    "voc": {"codec": None, "bitrate": None},                # Creative Voice
    "spx": {"codec": "libspeex", "bitrate": "24k"},         # Speex
    "sid": {"codec": None, "bitrate": None},                # SID (Commodore 64)
    "3gp": {"codec": "libopencore_amrnb", "bitrate": "12.2k", "ar": "8000", "ac": "1"},
    "3gpp2": {"codec": "libopencore_amrnb", "bitrate": "12.2k", "f": "3gp", "ar": "8000", "ac": "1"}
}
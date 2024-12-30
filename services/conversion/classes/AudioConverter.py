import os
import subprocess
from abc import ABC
from collection.audio_codec import audio_codec
from classes.ConverterBase import ConverterBase

class AudioConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = os.path.splitext(output_path)[1].lstrip('.').lower()

        codec_params = audio_codec.get(extension, {})
        codec = codec_params.get("codec")
        bitrate = codec_params.get("bitrate")
        format_flag = codec_params.get("f")
        ar = codec_params.get("ar")
        ac = codec_params.get("ac")

        try:
            command = [
                "ffmpeg", "-i", file_path
            ]

            if codec:
                command.extend(["-acodec", codec])

            if bitrate:
                command.extend(["-b:a", bitrate])

            if format_flag:
                command.extend(["-f", format_flag])

            if ar:
                command.extend(["-ar", ar])

            if ac:
                command.extend(["-ac", ac])

            command.extend([output_path])

            result = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            if result.returncode != 0:
                raise Exception(f"FFmpeg error: {result.stderr.decode('utf-8')}")

        except Exception as e:
            raise Exception(f"Error converting audio: {str(e)}")

        return output_path

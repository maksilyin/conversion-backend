import os
import subprocess
from abc import ABC
from collection.video_codec import video_codec
from classes.ConverterBase import ConverterBase

class VideoConverter(ConverterBase, ABC):
    def convert(self) -> str:
        output_path = self._get_output_path()
        file_path = self._file_path
        extension = os.path.splitext(output_path)[1].lstrip('.').lower()

        codec_params = video_codec.get(extension, {})
        codec = codec_params.get("codec")
        bitrate = codec_params.get("bitrate")
        format_flag = codec_params.get("f")
        fps = codec_params.get("fps")
        scale = codec_params.get("scale")
        ar = codec_params.get("ar")
        ac = codec_params.get("ac")
        vf = codec_params.get("vf")
        rtbufsize = codec_params.get("rtbufsize")
        bufsize = codec_params.get("bufsize")
        maxrate = codec_params.get("maxrate")
        max_muxing_queue_size = codec_params.get("max_muxing_queue_size")

        if not bitrate:
            bitrate = self.get_bitrate(self._file_path)
        try:
            command = [
                "ffmpeg", "-i", file_path
            ]
            if codec:
                command.extend(["-vcodec", codec])
            if bitrate:
                command.extend(["-b:v", bitrate])
            if format_flag:
                command.extend(["-f", format_flag])
            if ar:
                command.extend(["-ar", ar])
            if ac:
                command.extend(["-ac", ac])
            if vf:
                command.extend(["-vf", vf])
            if rtbufsize:
                command.extend(["-rtbufsize", rtbufsize])
            if bufsize:
                command.extend(["-bufsize", bufsize])
            if maxrate:
                command.extend(["-maxrate", maxrate])
            if max_muxing_queue_size:
                command.extend(["-max_muxing_queue_size", max_muxing_queue_size])
            """
            if fps:
                command.extend(["-r", fps])

            if scale:
                command.extend(["-vf", f"scale={scale}"])
            """
            command.extend([output_path])

            result = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE)
            if result.returncode != 0:
                raise Exception(f"FFmpeg error: {result.stderr.decode('utf-8')}")

        except Exception as e:
            raise Exception(f"Error converting video: {str(e)}")

        return output_path

    def get_video_scale(self, file_path):
        try:
            command = [
                "ffprobe", "-v", "error",
                "-select_streams", "v:0",
                "-show_entries", "stream=width,height",
                "-of", "csv=p=0",
                file_path
            ]
            result = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
            if result.returncode != 0:
                raise Exception(f"FFprobe error: {result.stderr}")

            # Парсинг результата
            width, height = map(int, result.stdout.strip().split(","))
            return width, height
        except Exception as e:
            raise Exception(f"Error retrieving video scale: {str(e)}")

    def get_bitrate(self, file_path):
        try:
            command = [
                "ffprobe", "-v", "error",
                "-show_entries", "format=bit_rate",
                "-of", "default=noprint_wrappers=1:nokey=1",
                file_path
            ]
            result = subprocess.run(command, stdout=subprocess.PIPE, stderr=subprocess.PIPE, text=True)
            if result.returncode != 0:
                return None
            return str(int(result.stdout.strip()) / 1000) + "k"
        except Exception as e:
            return None

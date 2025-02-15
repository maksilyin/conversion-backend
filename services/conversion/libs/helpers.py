from collection.FileFormats import raw_formats, image_formats, vector_formats, audio_formats, video_formats, \
    archive_formats, font_formats, document_formats, ebook_formats, presentation_formats, spreadsheets_formats


def get_file_type_by_format(file_format):
    result = []

    formats = {
        'raw': raw_formats,
        'image': image_formats,
        'vector': vector_formats,
        'audio': audio_formats,
        'video': video_formats,
        'document': document_formats,
        'archive': archive_formats,
        'font': font_formats,
        'ebook': ebook_formats,
        'presentation': presentation_formats,
        'spreadsheets': spreadsheets_formats
    }
    for file_type in formats:
        if file_format in formats[file_type]:
            result.append(file_type)
    return result


def is_type_by_format(file_type, file_format):
    file_types = get_file_type_by_format(file_format)
    return file_type in file_types

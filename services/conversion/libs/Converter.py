def convert_file(strategy, input_file, output_file=None, output_format=None):
    from classes.ArchiveConverter import ArchiveConverter
    from classes.AudioConverter import AudioConverter
    from classes.DocumentConverter import DocumentConverter
    from classes.EbookConverter import EbookConverter
    from classes.FontConverter import FontConverter
    from classes.ImageConverter import ImageConverter
    from classes.PdfConverter import PdfConverter
    from classes.SpreadsheetsConverter import SpreadsheetsConverter
    from classes.VectorConverter import VectorConverter
    from classes.VideoConverter import VideoConverter

    converters = {
        "image": ImageConverter,
        "vector": VectorConverter,
        "audio": AudioConverter,
        "video": VideoConverter,
        "ebook": EbookConverter,
        "document": DocumentConverter,
        "font": FontConverter,
        "archive": ArchiveConverter,
        "spreadsheet": SpreadsheetsConverter,
        "pdf": PdfConverter,
    }
    
    if not strategy in converters:
        raise NotImplementedError(f"Conversion from {strategy} to {output_format} is not supported.")

    converter = converters[strategy](input_file, output_format, output_file)
    return converter.convert()

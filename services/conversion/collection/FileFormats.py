raw_formats = [
    "3fr",    # Hasselblad RAW
    "ari",    # ARRIFLEX RAW
    "arw",    # Sony Alpha RAW
    "bay",    # Casio RAW
    "cap",    # Phase One RAW
    "cin",    # Cineon Image File
    "crw",    # Canon RAW (older)
    "cr2",    # Canon RAW (newer)
    "cr3",    # Canon RAW (latest)
    "cs1",    # Sinar CaptureShop RAW
    "data",   # RAW Data File
    "dcs",    # Kodak RAW
    "dcr",    # Kodak RAW
    "drf",    # Leaf RAW
    "eip",    # Phase One Enhanced Image Package
    "erf",    # Epson RAW
    "fff",    # Imacon/Hasselblad RAW
    "gpr",    # GoPro RAW
    "iiq",    # Phase One RAW
    "k25",    # Kodak RAW
    "kdc",    # Kodak RAW
    "mdc",    # Minolta RAW
    "mef",    # Mamiya RAW
    "mos",    # Leaf RAW
    "mrw",    # Minolta RAW
    "nef",    # Nikon RAW
    "nrw",    # Nikon RAW (newer)
    "orf",    # Olympus RAW
    "pef",    # Pentax RAW
    "ptx",    # Pentax RAW
    "pxn",    # Logitech RAW
    "raf",    # Fuji RAW
    "raw",    # General RAW format
    "rdc",    # Ricoh RAW
    "rw2",    # Panasonic RAW
    "rwl",    # Leica RAW
    "sr2",    # Sony RAW
    "srf",    # Sony RAW
    "srw",    # Samsung RAW
    "x3f",    # Sigma/Foveon RAW
    "dng",    # Adobe Digital Negative
    "mrf",    # Mamiya RAW Format
    "ios",    # Sinar RAW
    "ia",     # RAW format by unknown
    "lx",     # RawLux (LightZone RAW)
    "sti",    # Sinar RAW File
]
image_formats = [
    "jpeg", "jpg", "png", "gif", "bmp", "tiff", "tif", "webp", "ico",
    "heic", "heif", "apng", "mng", "flif", "dds", "exr", "hdr", "sis",
    "pict", "pcx", "xcf", "tga", "targa", "emf", "wmf", "avif", "jxl",
    "jfif", "ras", "rgbe", "qoi", "art", "icns", "ani", "xpm", "xbm",
    "pgm", "ppm", "pbm", "sgi", "ipl", "fit", "fits", "cpt", "img",
    "im", "rle", "j2k", "jp2", "jpf", "jpx", "jpm", "miff", "palm",
    "prc", "mpo", "ptx", "mtv", "pix", "cin", "viff", "cals", "als",
    "sct", "tim", "xwd", "fpx", "tifflzw", "ciff", "jpeg2000", "pgf",
    "bpg", "wbmp", "djvu", "ilm", "pcd", "exif", "col", "8bf", "jpg2k",
    "mtvbitmap"
]

vector_formats = [
    "svg",  # Scalable Vector Graphics, веб-стандарт для векторной графики
    "eps",  # Encapsulated PostScript, часто используется в печати
    "pdf",  # Portable Document Format, поддерживает векторную графику
    "ai",   # Adobe Illustrator, проприетарный формат Adobe
    "cgm",  # Computer Graphics Metafile, используется для технических чертежей
    "dxf",  # Drawing Exchange Format, формат для CAD-систем
    "dwg",  # Формат AutoCAD для чертежей
    "emf",  # Enhanced Metafile, улучшенный формат Windows Metafile
    "wmf",  # Windows Metafile, поддерживает векторные и растровые данные
    "skp",  # SketchUp, для 3D-моделирования
    "odg",  # OpenDocument Drawing, используется в LibreOffice Draw
    "xar",  # Формат Xara, векторный графический редактор
    "sxd",  # OpenOffice Draw формат
    "fxg",  # Flash XML Graphics, формат Adobe Flash
    "plt",  # HPGL Plot File, для плоттеров
    "svgz", # Сжатый SVG
    "cdr",  # CorelDRAW, проприетарный формат
    "vml",  # Vector Markup Language, устаревший формат Microsoft
    "vsd",  # Microsoft Visio, используется для диаграмм
    "gml",  # Geography Markup Language, для географических данных
    "igs",  # Initial Graphics Exchange Specification, для CAD-систем
    "stl",  # Stereolithography, формат для 3D-моделей
    "ufo",  # Ulead File for Objects, используется в графическом редакторе Ulead
    "p2v",  # PostScript-to-vector, конвертированный формат
    "amf",  # Additive Manufacturing File, используется для 3D-печати
    "grf",  # Графический формат для инженерных приложений
    "art",  # ArtCAM, векторный формат для гравировки и резьбы
    "hpgl", # Hewlett-Packard Graphics Language, для плоттеров
    "xml",  # Векторные данные могут быть закодированы в XML
    "geojson" # Геопространственный формат для карт, поддерживает векторные данные
]

audio_formats = [
    # Сжатые форматы с потерями
    "mp3",    # MPEG Audio Layer III, самый популярный аудиоформат
    "aac",    # Advanced Audio Coding, более эффективный, чем MP3
    "wma",    # Windows Media Audio, проприетарный формат Microsoft
    "ogg",    # Формат с открытым исходным кодом, использует кодек Vorbis
    "opus",   # Современный формат для потоковой передачи
    "amr",    # Adaptive Multi-Rate, используется в телефонии

    # Сжатые форматы без потерь
    "flac",   # Free Lossless Audio Codec, открытый стандарт
    "alac",   # Apple Lossless Audio Codec, используется в продуктах Apple
    "ape",    # Monkey's Audio, формат сжатия без потерь
    "wv",     # WavPack, поддерживает как сжатие без потерь, так и с потерями
    "tta",    # True Audio, формат без потерь
    "shn",    # Shorten, ранний формат сжатия без потерь

    # Несжатые форматы
    "wav",    # Waveform Audio File Format, стандартный формат Windows
    "aiff",   # Audio Interchange File Format, стандарт Apple
    "pcm",    # Pulse-Code Modulation, используется для необработанного аудио
    "bwf",    # Broadcast Wave Format, расширение WAV для профессиональной записи

    # Мультитрековые и модульные форматы
    "midi",   # Musical Instrument Digital Interface, для музыкальных секвенций
    "mod",    # Формат модульной музыки, включает инструменты и треки
    "xm",     # Extended Module, улучшенная версия MOD
    "it",     # Impulse Tracker, модульный аудиоформат
    "s3m",    # Scream Tracker, модульный формат
    "mt2",    # MadTracker 2, мультитрековый формат

    # Форматы для потоковой передачи и телекоммуникаций
    "rtp",    # Real-time Transport Protocol, используется для передачи аудио в реальном времени
    "m4a",    # Формат для аудио внутри контейнера MP4
    "webm",   # Формат для веб-аудио на основе Opus/Vorbis
    "caf",    # Core Audio Format, разработан Apple для профессионального аудио

    # Проприетарные и редкие форматы
    "dsd",    # Direct Stream Digital, используется в SACD
    "dff",    # Формат DSD, используемый в профессиональном аудио
    "sln",    # Raw аудио, используется в Asterisk
    "vox",    # Формат с низким качеством для телефонии
    "gsm",    # Формат для голосовой телефонии
    "ra",     # RealAudio, формат для потокового аудио
    "rm",     # RealMedia, контейнер, включающий аудио

    # Контейнеры, поддерживающие аудио
    "mp4",    # Контейнер, поддерживающий аудио (включая AAC, ALAC и др.)
    "mkv",    # Matroska, поддерживает аудио внутри контейнера
    "avi",    # Контейнер, включающий аудио и видео
    "mov",    # Apple QuickTime, может содержать аудио
    "asf",    # Advanced Systems Format, для потокового контента
    "flv",    # Flash Video, может включать аудиодорожки
]

video_formats = [
    # Популярные контейнеры
    "mp4",    # MPEG-4 Part 14, самый популярный контейнер
    "mkv",    # Matroska, поддерживает множество кодеков
    "avi",    # Audio Video Interleave, популярный в Windows
    "mov",    # Apple QuickTime, стандартный формат Apple
    "wmv",    # Windows Media Video, проприетарный формат Microsoft
    "flv",    # Flash Video, ранее использовался в вебе
    "webm",   # Формат для веба, использует VP9/Opus
    "m4v",    # MPEG-4 Video, часто используется в iTunes
    "3gp",    # Формат для мобильных устройств
    "ogg",    # Контейнер, поддерживающий видео (Theora) и аудио (Vorbis/Opus)

    # Старые или специфичные контейнеры
    "mpg",    # MPEG-1/MPEG-2 Video, для старых DVD
    "mpeg",   # Аналогично MPG
    "ts",     # MPEG Transport Stream, используется для потокового ТВ
    "vob",    # Video Object, формат DVD-дисков
    "m2ts",   # Blu-ray Transport Stream, для Blu-ray дисков
    "asf",    # Advanced Systems Format, используется с WMV
    "f4v",    # Flash Video (вариант MP4)
    "rm",     # RealMedia, старый формат потокового видео
    "rmvb",   # RealMedia Variable Bitrate, улучшенный RM
    "amv",    # Формат для портативных плееров
    "divx",   # Формат от DivX Inc., основан на MPEG-4
    "xvid",   # Альтернативный формат DivX, тоже на MPEG-4

    # Высокое качество и профессиональные форматы
    "mov",    # QuickTime, используется в профессиональной среде
    "prores", # Apple ProRes, для профессионального монтажа
    "dnxhd",  # Avid DNxHD, профессиональный формат
    "cineform", # GoPro CineForm, для монтажа
    "h264",   # Кодек, часто используется в контейнерах MP4/MKV
    "h265",   # HEVC, более современный и эффективный, чем H.264
    "yuv",    # Сырой формат видео (без сжатия)
    "mxf",    # Material Exchange Format, профессиональный стандарт
    "dpx",    # Digital Picture Exchange, для кино

    # Для потокового вещания
    "rtsp",   # Протокол для потокового видео
    "rtmp",   # Протокол для потокового видео (Flash)
    "hls",    # HTTP Live Streaming, от Apple
    "dash",   # Dynamic Adaptive Streaming over HTTP

    # Форматы с поддержкой анимации
    "gifv",   # Видео-версия GIF
    "webp",   # Видео- и анимационные возможности WebP
    "apng",   # Анимационный PNG (видео-аналог)

    # Форматы виртуальной реальности и 360° видео
    "vr",     # Видео для VR-устройств
    "360",    # 360-градусное видео, часто в контейнерах MP4/MKV

    # Экзотические и устаревшие форматы
    "ivf",    # Indeo Video Format, старый формат
    "bik",    # Bink Video, используется в играх
    "smk",    # Smacker Video, для старых игр
    "dpg",    # Nintendo DS Video
    "mts",    # AVCHD, формат для видеокамер
    "fli",    # Autodesk Animator
    "flc",    # Расширение формата FLI
    "roq",    # Использовался в Quake III

    # Форматы для анимации и графики
    "swf",    # Adobe Flash, поддерживает анимацию
    "mve",    # Файл анимации, использовался в старых играх

    # Видео в научных и медицинских приложениях
    "dicom",  # Видео в медицинских исследованиях
    "czi",    # Видео для микроскопов

    # Форматы, поддерживающие HDR и 4K/8K
    "hdr",    # Видео с поддержкой высокого динамического диапазона
    "uhd",    # Формат для 4K/8K видео

    # Контейнеры, поддерживающие видео и аудио
    "ogm",    # Ogg Media, расширение для OGG
    "nut",    # Старый контейнер, созданный FFmpeg
]

document_formats = [
    # Текстовые документы
    "doc",     # Microsoft Word, старый формат
    "docx",    # Microsoft Word, современный формат
    "rtf",     # Rich Text Format, поддерживает форматирование
    "txt",     # Plain Text, простой текстовый формат
    "odt",     # OpenDocument Text, используется в LibreOffice
    "wps",     # Microsoft Works, устаревший формат
    "wpd",     # WordPerfect Document, устаревший
    "sxw",     # OpenOffice Text Document, устаревший

    # Форматы для презентаций
    "ppt",     # Microsoft PowerPoint, старый формат
    "pptx",    # Microsoft PowerPoint, современный формат
    "odp",     # OpenDocument Presentation, используется в LibreOffice
    "pps",     # PowerPoint Slide Show, старый
    "ppsx",    # PowerPoint Slide Show, современный
    "key",     # Apple Keynote Presentation

    # Таблицы
    "xls",     # Microsoft Excel, старый формат
    "xlsx",    # Microsoft Excel, современный формат
    "ods",     # OpenDocument Spreadsheet, используется в LibreOffice
    "csv",     # Comma-Separated Values, табличные данные в текстовом виде
    "tsv",     # Tab-Separated Values, аналог CSV с табуляцией
    "xltx",    # Шаблоны Microsoft Excel

    # Форматы электронных книг
    "epub",    # Electronic Publication, открытый стандарт
    "mobi",    # Формат для Kindle
    "azw",     # Amazon Kindle Format
    "azw3",    # Новая версия Amazon Kindle Format
    "fb2",     # FictionBook, популярный в России
    "lit",     # Microsoft Reader, устаревший
    "ibooks",  # Формат для Apple iBooks
    "cbr",     # Comic Book Archive (RAR)
    "cbz",     # Comic Book Archive (ZIP)
    "djvu",    # Формат для сканированных книг

    # PDF и его производные
    "pdf",     # Portable Document Format, самый популярный
    "xps",     # XML Paper Specification, альтернатива PDF
    "oxps",    # Open XML Paper Specification
    "ps",      # PostScript, предшественник PDF

    # Форматы для научных и инженерных документов
    "tex",     # LaTeX, используется для научных работ
    "dvi",     # Device Independent File Format, для TeX
    "lyx",     # LyX Document, LaTeX-редактор
    "gdoc",    # Google Docs
    "nb",      # Mathematica Notebook
    "nbp",     # Mathematica Player Notebook
    "rtfd",    # Rich Text Format Directory (MacOS)

    # Устаревшие форматы
    "abw",     # AbiWord Document
    "zabw",    # Compressed AbiWord Document
    "sdw",     # StarOffice Writer
    "gslides", # Google Slides
    "gspread", # Google Sheets
]

archive_formats = [
    # Популярные форматы архивов
    "zip",     # Самый популярный формат, поддерживается почти везде
    "rar",     # Проприетарный формат с высокой степенью сжатия
    "7z",      # Формат с высоким уровнем сжатия, разработанный 7-Zip
    "tar",     # Формат архива без сжатия, часто используется в Linux
    "gz",      # Gzip, используется для сжатия файлов, включая tar-архивы (например, .tar.gz)
    "bz2",     # Bzip2, формат сжатия, часто используется с tar
    "xz",      # Формат сжатия, аналогичный bz2, но с более высокой эффективностью
    "tgz",     # Сокращение для tar.gz
    "tbz",     # Сокращение для tar.bz2
    "txz",     # Сокращение для tar.xz

    # Старые или менее популярные форматы архивов
    "z",       # Compress, устаревший формат сжатия
    "lz",      # Lzip, формат с открытым исходным кодом
    "lzma",    # Lempel-Ziv-Markov Chain Algorithm, формат сжатия
    "cab",     # Cabinet, формат архивов Windows
    "arj",     # Старый формат архивов
    "lzh",     # Формат Lempel-Ziv-Huffman, популярный в Японии
    "ace",     # Проприетарный формат архивов (устарел)
    "iso",     # Образы дисков CD/DVD
    "img",     # Образы дисков, могут содержать архивированные данные
    "nrg",     # Nero Disc Image
    "daa",     # PowerISO Direct-Access-Archive

    # Архивы для Unix/Linux
    "cpio",    # Формат архива в Unix
    "rpm",     # Red Hat Package Manager, содержит сжатые данные
    "deb",     # Debian Package, используется для установки программ в Debian/Ubuntu
    "apk",     # Android Package, архив для приложений Android
    "pkg",     # Формат пакетов macOS

    # Специализированные форматы
    "jar",     # Java Archive, архив для Java-приложений
    "war",     # Web Application Archive, расширение для JAR
    "ear",     # Enterprise Archive, архив Java EE
    "xpi",     # Расширения Mozilla (Firefox, Thunderbird)
    "crx",     # Расширения Google Chrome
    "appx",    # Архив приложений Windows
    "msix",    # Современный архив приложений Windows
    "sar",     # Service Archive, используется в Java

    # Менее известные архивные форматы
    "paq",     # PAQ, формат с максимальным сжатием
    "zpaq",    # ZPAQ, формат сжимающий данные и поддерживающий версии
    "arc",     # Устаревший формат архивов
    "sit",     # StuffIt, формат для macOS
    "sitx",    # StuffIt X, улучшенная версия StuffIt
    "ha",      # HA Archive, устаревший формат
    "alz",     # Формат архивов для Windows
    "peazip",  # Архивный формат PeaZip
    "xzfx",    # Специализированный архив

    # Контейнеры, поддерживающие архивы
    "dmg",     # Образы дисков macOS
    "vhd",     # Образы виртуальных жестких дисков
    "vmdk",    # Виртуальные диски VMware
    "qcow2",   # Формат виртуальных дисков QEMU
]

font_formats = [
    # Популярные форматы шрифтов
    "ttf",     # TrueType Font, стандартный формат шрифтов
    "otf",     # OpenType Font, расширение TTF с поддержкой дополнительных функций
    "woff",    # Web Open Font Format, оптимизирован для использования в вебе
    "woff2",   # Улучшенная версия WOFF с более высокой степенью сжатия
    "eot",     # Embedded OpenType, формат для веба, поддерживается Internet Explorer

    # Растровые шрифты
    "bdf",     # Bitmap Distribution Format, используется в X11
    "pcf",     # Portable Compiled Format, компактный формат растровых шрифтов
    "fnt",     # Растровый формат, использовался в старых версиях Windows
    "fon",     # Старый формат растровых шрифтов Windows

    # Векторные шрифты
    "svg",     # Scalable Vector Graphics, шрифты в формате SVG
    "cff",     # Compact Font Format, часть OpenType
    "pfa",     # PostScript Font ASCII, шрифт в формате PostScript
    "pfb",     # PostScript Font Binary, двоичная версия PFA
    "afm",     # Adobe Font Metrics, метрики шрифтов PostScript

    # Специализированные форматы
    "dfont",   # Data Fork Font, старый формат macOS
    "suit",    # Suitcase, старый формат шрифтов для macOS
    "ttc",     # TrueType Collection, коллекция шрифтов TTF
    "otc",     # OpenType Collection, коллекция шрифтов OTF
    "mtx",     # MicroType Express, используется Monotype Imaging
    "vlw",     # Processing Font, для программ на Processing

    # Шрифты для веба и встроенных устройств
    "pfr",     # Portable Font Resource, используется в старых браузерах
    "xfn",     # Формат шрифтов X Window System
    "psf",     # PC Screen Font, для консолей Linux

    # Устаревшие и редкие форматы
    "t42",     # Type 42, формат PostScript шрифта
    "gxf",     # Формат для графических приложений
    "snf",     # Server Normal Format, устаревший формат для X11
    "bit",     # Bitmap Font, устаревший формат растровых шрифтов
    "vfb",     # FontLab Studio, собственный формат редактирования шрифтов
    "chr",     # Устаревший формат для DOS-программ
    "spd",     # Speedo Fonts, использовался на Unix
    "fot",     # Font Resource File, устаревший формат Windows

    # Форматы для работы с шрифтами
    "glyphs",  # Формат для Glyphs, редактора шрифтов
    "ufo",     # Unified Font Object, формат для редактирования шрифтов
    "sfdir",   # Spline Font Directory, используется FontForge
    "fea",     # Adobe Feature File, настройки OpenType
    "json",    # Используется для метаинформации шрифтов
    "xml",     # Формат данных о шрифтах
]

ebook_formats = [
    # Популярные форматы электронных книг
    "epub",    # Electronic Publication, открытый стандарт для eBook
    "mobi",    # Формат для Amazon Kindle
    "azw",     # Amazon Kindle Format, проприетарный формат
    "azw3",    # Kindle Format 8 (KF8), поддерживает HTML5 и CSS3
    "pdf",     # Portable Document Format, часто используется для eBook
    "fb2",     # FictionBook, популярный формат в России
    "lit",     # Microsoft Reader, устаревший формат

    # Комиксы
    "cbr",     # Comic Book Archive (RAR), формат для комиксов
    "cbz",     # Comic Book Archive (ZIP), аналог CBR
    "cb7",     # Comic Book Archive (7z), для архивации комиксов
    "cbt",     # Comic Book Archive (TAR)
    "cba",     # Comic Book Archive (ACE)

    # Форматы для чтения на устройствах
    "ibooks",  # Формат для Apple iBooks
    "kfx",     # Kindle Format 10, современный формат Kindle
    "kf8",     # Kindle Format 8, аналог azw3
    "boc",     # Формат Bookeen Cybook
    "djvu",    # Формат для сканированных книг с высоким сжатием
    "zno",     # Format для Magzter eBooks

    # Старые и редкие форматы
    "pdb",     # Palm Database File, использовался в Palm Reader
    "prc",     # Palm Resource File, аналог MOBI
    "tr2",     # TomeRaider 2, устаревший формат
    "tr3",     # TomeRaider 3, улучшенная версия TR2
    "oxps",    # Open XML Paper Specification, альтернатива PDF
    "xps",     # XML Paper Specification, предшественник OXPS

    # Форматы для чтения на специальных устройствах
    "bbl",     # eReader (Barnes & Noble Nook)
    "nook",    # Формат для Barnes & Noble Nook
    "lrf",     # Sony Portable Reader Format, устаревший
    "lrx",     # Sony Portable Reader Format, улучшенная версия LRF
    "rb",      # Rocket eBook, устаревший формат
    "tcr",     # FictionBook Reader, старый формат
]

presentation_formats = [
    # Популярные форматы презентаций
    "ppt",     # Microsoft PowerPoint, старый формат
    "pptx",    # Microsoft PowerPoint, современный формат
    "pps",     # PowerPoint Slide Show, старый формат
    "ppsx",    # PowerPoint Slide Show, современный формат
    "odp",     # OpenDocument Presentation, используется в LibreOffice
    "key",     # Apple Keynote Presentation, формат для macOS
    "gslides", # Google Slides, используется в Google Workspace
]

spreadsheets_formats = [
    "xls",     # Microsoft Excel, старый формат
    "xlsx",    # Microsoft Excel, современный формат
    "ods",     # OpenDocument Spreadsheet, используется в LibreOffice
    "csv",     # Comma-Separated Values, табличные данные в текстовом виде
    "tsv",     # Tab-Separated Values, аналог CSV с табуляцией
    "xltx",    # Шаблоны Microsoft Excel
]
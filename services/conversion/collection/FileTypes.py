fileTypes = {
    'image': [
         # Common image formats
        "image/jpeg",                  # JPEG изображения
        "image/png",                   # PNG изображения
        "image/gif",                   # GIF изображения
        "image/bmp",                   # BMP изображения
        "image/webp",                  # WebP изображения
        "image/tiff",                  # TIFF изображения
        "image/x-icon",                # Иконка ICO
        "image/vnd.microsoft.icon",    # Microsoft ICO
        "image/vnd.adobe.photoshop",   # PSD изображения (Adobe Photoshop)

        # HEIC/HEIF
        "image/heic",                  # HEIC изображения
        "image/heif",                  # HEIF изображения

        # Legacy and specialized formats
        "image/x-portable-pixmap",     # PPM изображения
        "image/x-portable-bitmap",     # PBM изображения
        "image/x-portable-graymap",    # PGM изображения
        "image/x-portable-anymap",     # PNM изображения
        "image/x-cmu-raster",          # CMU Raster
        "image/x-xbitmap",             # XBM изображения
        "image/x-xpixmap",             # XPM изображения
        "image/x-targa",               # TGA изображения
        "image/x-ms-bmp",              # BMP (V2/V3)
        "image/x-photoshop",           # Photoshop Images

        # Other less common
        "image/x-icns",                # Apple Icon Image
        "image/x-pcx",                 # PCX изображения
        "image/x-sgi",                 # SGI изображения
        "image/x-rgb",                 # RGB изображения
        "image/x-rle",                 # Utah RLE изображения
        "image/vnd.dxf",               # AutoCAD DXF
        "image/x-cals",                # CALS Raster
        "image/x-fits",                # Flexible Image Transport System (FITS)
        "image/x-dpx",                 # DPX изображения
        "image/vnd.fastbidsheet",      # FastBid Sheet

        # HDR and advanced image types
        "image/vnd.radiance",          # Radiance HDR
        "image/x-exr",                 # OpenEXR изображения
        "image/vnd.wap.wbmp",          # WBMP изображения
        "image/x-raw-bayer",           # Raw Bayer Format
        "image/vnd.wap.wbmp",          # Wireless Bitmap
    ],
    'raw': [
        # RAW Image formats
        "image/x-canon-cr2",           # Canon CR2
        "image/x-canon-crw",           # Canon CRW
        "image/x-sony-arw",            # Sony ARW
        "image/x-adobe-dng",           # Digital Negative (DNG)
        "image/x-nikon-nef",           # Nikon NEF
        "image/x-olympus-orf",         # Olympus ORF
        "image/x-panasonic-raw",       # Panasonic RAW
        "image/x-pentax-pef",          # Pentax PEF
        "image/x-fuji-raf",            # Fuji RAF
        "image/x-minolta-mrw",         # Minolta MRW
        "image/x-sigma-x3f",           # Sigma X3F
        "image/x-phaseone-iiq",        # PhaseOne IIQ
        "image/x-sony-srf",            # Sony SRF
        "image/x-sony-sr2",            # Sony SR2
    ],
    'document': [
        # PDF и PostScript
        "application/pdf",                    # PDF документ
        "application/postscript",             # PostScript документ (EPS)

        # Microsoft Word и Office
        "application/msword",                 # Microsoft Word (.doc)
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",  # Word (.docx)
        "application/vnd.ms-word.document.macroEnabled.12",         # Word с макросами (.docm)
        "application/vnd.ms-excel",           # Microsoft Excel (.xls)
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",  # Excel (.xlsx)
        "application/vnd.ms-powerpoint",      # Microsoft PowerPoint (.ppt)
        "application/vnd.openxmlformats-officedocument.presentationml.presentation",  # PowerPoint (.pptx)

        # OpenDocument Format (ODF)
        "application/vnd.oasis.opendocument.text",          # OpenDocument текст (.odt)
        "application/vnd.oasis.opendocument.spreadsheet",   # OpenDocument таблицы (.ods)
        "application/vnd.oasis.opendocument.presentation",  # OpenDocument презентация (.odp)
        "application/vnd.oasis.opendocument.graphics",      # OpenDocument графика (.odg)

        # Текстовые форматы
        "text/plain",                     # Обычный текст (.txt)
        "text/csv",                       # CSV файл
        "text/tab-separated-values",      # TSV файл
        "text/html",                      # HTML документ
        "application/xhtml+xml",          # XHTML документ

        # Электронные книги
        "application/epub+zip",           # EPUB электронная книга
        "application/x-mobipocket-ebook", # MOBI электронная книга
        "application/vnd.amazon.ebook",   # AZW электронная книга (Kindle)

        # RTF и Latex
        "application/rtf",                # Rich Text Format (.rtf)
        "application/x-latex",            # LaTeX документ
        "application/x-tex",              # TeX документ
        "application/x-texinfo",          # Texinfo документ

        # Другие форматы
        "application/x-dvi",              # DVI документ
        "application/x-abiword",          # AbiWord документ
        "application/vnd.google-apps.document",  # Google Docs
        "application/x-tika-ooxml",       # Tika OOXML
        "application/x-hwp",              # Hangul Word Processor
        "application/vnd.apple.pages",    # Apple Pages
    ],
    'audio': [
        # Common audio formats
        "audio/mpeg",                    # MP3
        "audio/wav",                     # WAV
        "audio/x-wav",                   # WAV (alternative MIME)
        "audio/ogg",                     # OGG
        "audio/vorbis",                  # Vorbis
        "audio/webm",                    # WebM Audio
        "audio/aac",                     # AAC
        "audio/x-aac",                   # AAC (alternative MIME)
        "audio/flac",                    # FLAC
        "audio/x-flac",                  # FLAC (alternative MIME)
        "audio/mp4",                     # MP4 Audio
        "audio/x-m4a",                   # M4A Audio
        "audio/opus",                    # Opus Audio
        "audio/3gpp",                    # 3GPP Audio
        "audio/3gpp2",                   # 3GPP2 Audio

        # MIDI formats
        "audio/midi",                    # MIDI
        "audio/x-midi",                  # MIDI (alternative MIME)

        # RealAudio formats
        "audio/x-realaudio",             # RealAudio
        "audio/vnd.rn-realaudio",        # RealAudio (alternative MIME)

        # AIFF formats
        "audio/aiff",                    # AIFF
        "audio/x-aiff",                  # AIFF (alternative MIME)

        # AMR formats
        "audio/amr",                     # AMR
        "audio/x-amr",                   # AMR (alternative MIME)

        # Other specialized audio formats
        "audio/x-ms-wma",                # WMA (Windows Media Audio)
        "audio/x-pn-wav",                # WAV (alternative MIME)
        "audio/x-matroska",              # Matroska Audio (MKA)
        "audio/basic",                   # Basic Audio (mulaw, 8kHz, 1 channel)
        "audio/x-mpegurl",               # M3U (Playlist)
        "audio/x-scpls",                 # PLS (Playlist)
        "audio/x-caf",                   # Apple Core Audio Format (CAF)
        "audio/x-aiff",                  # AIFF
        "audio/prs.sid",                 # SID (Commodore 64 music)
        "audio/vnd.dlna.adts",           # DLNA/ADTS
        "audio/vnd.rn-realaudio",        # RealAudio
        "audio/vnd.wave",                # WAVE (alternative MIME)
    ],
    'video': [
        # Common video formats
        "video/mp4",                     # MP4
        "video/x-m4v",                   # M4V
        "video/mpeg",                    # MPEG
        "video/x-msvideo",               # AVI
        "video/avi",                     # AVI (alternative MIME)
        "video/quicktime",               # MOV
        "video/x-ms-wmv",                # WMV (Windows Media Video)
        "video/webm",                    # WebM
        "video/ogg",                     # OGG Video
        "video/3gpp",                    # 3GPP
        "video/3gpp2",                   # 3GPP2
        "video/x-flv",                   # FLV (Flash Video)
        "video/x-matroska",              # Matroska Video (MKV)
        "video/x-ms-asf",                # ASF (Advanced Systems Format)
        "video/x-f4v",                   # F4V (Flash Video)

        # Specialized formats
        "video/h264",                    # H.264
        "video/h265",                    # H.265
        "video/x-h264",                  # H.264 (alternative MIME)
        "video/x-h265",                  # H.265 (alternative MIME)
        "video/x-mpeg2",                 # MPEG-2
        "video/x-divx",                  # DivX
        "video/x-theora",                # Theora
        "video/dv",                      # DV (Digital Video)
        "video/x-dv",                    # DV (alternative MIME)

        # RealMedia formats
        "video/x-pn-realvideo",          # RealVideo
        "video/vnd.rn-realvideo",        # RealVideo (alternative MIME)

        # Apple formats
        "video/mp2t",                    # MPEG-2 Transport Stream
        "video/mp1s",                    # MPEG-1 Systems Stream
        "video/x-m4a",                   # M4A (Video/Audio Container)
        "video/x-prores",                # Apple ProRes

        # Other formats
        "video/x-ms-vob",                # VOB (DVD Video)
        "video/x-mpegurl",               # M3U8 (HTTP Live Streaming)
        "video/vnd.uvvu.mp4",            # UltraViolet MP4
        "video/x-smv",                   # SMV (Video for small devices)
        "video/x-cdxa",                  # CDXA Video (CD-ROM XA Video)
    ],
    'archive': [
        # ZIP и подобные форматы
        "application/zip",                     # ZIP
        "application/x-zip-compressed",        # ZIP (alternative MIME)
        "application/x-compressed",            # ZIP (alternative MIME)

        # Tar
        "application/x-tar",                   # TAR
        "application/x-gtar",                  # GTAR

        # GZip
        "application/gzip",                    # GZ
        "application/x-gzip",                  # GZ (alternative MIME)

        # 7z
        "application/x-7z-compressed",         # 7Z

        # RAR
        "application/vnd.rar",                 # RAR
        "application/x-rar-compressed",        # RAR (alternative MIME)

        # BZip2
        "application/x-bzip",                  # BZ2
        "application/x-bzip2",                 # BZ2 (alternative MIME)

        # LZMA и XZ
        "application/x-lzma",                  # LZMA
        "application/x-xz",                    # XZ

        # ISO образы
        "application/x-iso9660-image",         # ISO

        # CAB
        "application/vnd.ms-cab-compressed",   # CAB

        # WARC (Web Archive)
        "application/warc",                    # WARC (Web ARChive)

        # Комбинированные форматы
        "application/x-compressed-tar",        # TAR.GZ (TGZ)
        "application/x-gtar-compressed",       # TAR.GZ (alternative MIME)
        "application/x-tgz",                   # TAR.GZ (alternative MIME)

        # Z (старый Unix-архив)
        "application/x-compress",              # Z

        # JAR (Java Archive)
        "application/java-archive",            # JAR
        "application/x-java-archive",          # JAR (alternative MIME)

        # Пароли и шифрование
        "application/x-pkcs12",                # PKCS #12 (PFX)
        "application/x-pkcs7-certificates",    # PKCS #7 Certificates

        # Другие форматы
        "application/x-arj",                   # ARJ
        "application/x-lha",                   # LHA
        "application/x-zoo",                   # ZOO
        "application/vnd.android.package-archive",  # APK
        "application/x-cpio",                  # CPIO
        "application/x-rpm",                   # RPM
        "application/x-deb",                   # DEB (Debian Package)
    ],
    'font': [
        # TrueType Fonts
        "font/ttf",                      # TTF
        "application/x-font-ttf",        # TTF (alternative MIME)

        # OpenType Fonts
        "font/otf",                      # OTF
        "application/x-font-otf",        # OTF (alternative MIME)

        # Web Open Font Format
        "font/woff",                     # WOFF
        "application/font-woff",         # WOFF (alternative MIME)
        "font/woff2",                    # WOFF2
        "application/font-woff2",        # WOFF2 (alternative MIME)

        # Embedded OpenType
        "font/eot",                      # EOT
        "application/vnd.ms-fontobject", # EOT (alternative MIME)

        # SVG Fonts
        "font/svg",                      # SVG Fonts

        # Bitmap Fonts
        "application/x-font-bdf",        # BDF
        "application/x-font-pcf",        # PCF
        "application/x-font-linux-psf",  # Linux PSF Fonts

        # PostScript Fonts
        "application/x-font-type1",      # Type 1
        "application/x-font-afm",        # AFM (Adobe Font Metrics)
        "application/x-font-pfm",        # PFM (Printer Font Metrics)

        # Other Fonts
        "application/x-font-ghostscript", # Ghostscript Font
        "application/x-font-speedo",     # Speedo Font
        "application/x-font-dos",        # DOS Font
    ],
    'spreadsheet': [
        # Microsoft Excel Formats
        "application/vnd.ms-excel",                       # XLS
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",  # XLSX
        "application/vnd.ms-excel.sheet.macroenabled.12", # XLSM (Excel with Macros)
        "application/vnd.ms-excel.template.macroenabled.12", # XLTM (Excel Template with Macros)

        # OpenDocument Spreadsheet Formats
        "application/vnd.oasis.opendocument.spreadsheet",          # ODS
        "application/vnd.oasis.opendocument.spreadsheet-template", # OTS

        # CSV (Comma-Separated Values)
        "text/csv",                                     # CSV
        "application/csv",                              # CSV (alternative MIME)

        # TSV (Tab-Separated Values)
        "text/tab-separated-values",                   # TSV

        # Other Spreadsheet Formats
        "application/vnd.lotus-1-2-3",                 # Lotus 1-2-3
        "application/x-dbase",                         # dBase
        "application/x-dbf",                           # DBF
        "application/x-dos_ms_excel",                  # Legacy Excel Format
        "application/x-gnumeric",                      # Gnumeric
        "application/vnd.google-apps.spreadsheet",     # Google Sheets
    ],
    'presentation': [
        # Microsoft PowerPoint Formats
        "application/vnd.ms-powerpoint",                               # PPT
        "application/vnd.openxmlformats-officedocument.presentationml.presentation",  # PPTX
        "application/vnd.ms-powerpoint.presentation.macroenabled.12",  # PPTM (PowerPoint with Macros)
        "application/vnd.ms-powerpoint.template.macroenabled.12",      # POTM (PowerPoint Template with Macros)
        "application/vnd.ms-powerpoint.slideshow.macroenabled.12",     # PPSM (PowerPoint Slideshow with Macros)
        "application/vnd.ms-powerpoint.addin.macroenabled.12",         # PPAM (PowerPoint Add-in)

        # OpenDocument Presentation Formats
        "application/vnd.oasis.opendocument.presentation",          # ODP
        "application/vnd.oasis.opendocument.presentation-template", # OTP

        # Legacy and Other Formats
        "application/vnd.sun.xml.impress",              # OpenOffice Impress (SXI)
        "application/vnd.stardivision.impress",         # StarOffice Impress
        "application/vnd.lotus-freelance",              # Lotus Freelance Graphics
        "application/vnd.google-apps.presentation",     # Google Slides
    ],
    'vector': [
        # SVG (Scalable Vector Graphics)
        "image/svg+xml",                               # SVG
        "application/svg",                             # Alternative SVG MIME
        "application/svg+xml",                         # Alternative SVG MIME

        # EPS (Encapsulated PostScript)
        "application/postscript",                      # PS
        "application/eps",                             # EPS (alternative MIME)
        "application/x-eps",                           # EPS (alternative MIME)
        "image/x-eps",                                 # EPS (alternative MIME)

        # Adobe Illustrator
        "application/vnd.adobe.illustrator",           # AI

        # WMF (Windows Metafile)
        "application/x-msmetafile",                    # WMF
        "image/x-wmf",                                 # WMF (alternative MIME)

        # DXF (Drawing Exchange Format)
        "image/vnd.dxf",                               # DXF
        "application/vnd.dxf",                         # DXF (alternative MIME)

        # CDR (CorelDRAW)
        "application/x-coreldraw",                     # CDR
        "application/vnd.corel-draw",                  # CDR (alternative MIME)

        # Other Vector Formats
        "application/vnd.hp-hpgl",                     # HPGL (Hewlett-Packard Graphics Language)
        "application/vnd.kde.kontour",                 # Kontour (KDE vector format)
        "application/x-cgm",                           # CGM (Computer Graphics Metafile)
        "application/x-xfig",                          # XFig (vector format)
    ],
    '3d': [
        # OBJ (Wavefront 3D Object File)
        "model/obj",                                      # OBJ

        # STL (Stereolithography File)
        "model/stl",                                      # STL
        "application/vnd.ms-pki.stl",                     # STL (alternative MIME)

        # FBX (Autodesk FBX Interchange File)
        "model/vnd.fbx",                                  # FBX

        # GLTF (GL Transmission Format)
        "model/gltf+json",                                # GLTF (JSON)
        "model/gltf-binary",                              # GLB (Binary)

        # 3DS (Autodesk 3D Studio File)
        "model/vnd.3ds",                                  # 3DS

        # DAE (COLLADA)
        "model/vnd.collada+xml",                          # DAE

        # PLY (Polygon File Format)
        "model/ply",                                      # PLY

        # X3D (Extensible 3D Graphics)
        "model/x3d+xml",                                  # X3D (XML)
        "model/x3d-vrml",                                 # X3D (VRML)
        "model/x3d+binary",                               # X3D (Binary)

        # VRML (Virtual Reality Modeling Language)
        "model/vrml",                                     # VRML

        # USD (Universal Scene Description)
        "model/vnd.usd",                                  # USD
        "model/vnd.usdz+zip",                             # USDZ (Compressed)

        # STEP (Standard for the Exchange of Product Data)
        "model/step",                                     # STEP

        # IGES (Initial Graphics Exchange Specification)
        "model/iges",                                     # IGES

        # Parasolid (Binary and Text Formats)
        "model/vnd.parasolid.transmit.binary",            # Parasolid (Binary)
        "model/vnd.parasolid.transmit.text",              # Parasolid (Text)

        # VTK (Visualization Toolkit Format)
        "model/vnd.vtk",                                  # VTK

        # HPGL (Hewlett-Packard Graphics Language)
        "application/vnd.hp-hpgl",                        # HPGL

        # CGM (Computer Graphics Metafile)
        "application/x-cgm",                              # CGM

        # 3MF (3D Manufacturing Format)
        "model/3mf",                                      # 3MF

        # Other Formats
        "application/x-xfig",                             # FIG (Xfig)
        "application/x-xaml+xml",                         # XAML (3D)
        "model/x-raw",                                    # RAW 3D Data
    ],
    'database': [
        # SQL Database Files
        "application/sql",                                # SQL Script
        "text/plain",                                     # SQL Plain Text

        # SQLite
        "application/vnd.sqlite3",                        # SQLite Database
        "application/x-sqlite3",                          # SQLite (alternative MIME)

        # MySQL Dump
        "application/x-mysql",                            # MySQL Dump File

        # Microsoft Access
        "application/vnd.ms-access",                      # Access MDB
        "application/x-msaccess",                         # Access (alternative MIME)

        # PostgreSQL Dump
        "application/x-postgresql",                       # PostgreSQL Dump

        # MongoDB Backup
        "application/x-bson",                             # BSON (Binary JSON for MongoDB)
        "application/json",                               # MongoDB JSON Export

        # Oracle Database
        "application/x-oracle-dump",                      # Oracle Dump File

        # Parquet
        "application/vnd.apache.parquet",                 # Apache Parquet File

        # Cassandra
        "application/vnd.cassandra",                      # Apache Cassandra Data File

        # NoSQL (Generic JSON or YAML)
        "application/json",                               # JSON
        "application/x-yaml",                             # YAML

        # Excel as Database
        "application/vnd.ms-excel",                       # XLS (Used as Database)
        "application/vnd.openxmlformats-officedocument.spreadsheetml.sheet",  # XLSX

        # Other Database Formats
        "application/vnd.ms-works",                       # Microsoft Works Database
        "application/dbase",                              # dBASE Database
        "application/vnd.oasis.opendocument.database",    # ODB (OpenDocument Database)
        "application/x-hdf",                              # HDF (Hierarchical Data Format)
        "application/x-sas",                              # SAS Dataset
        "application/vnd.ms-pki.seccat",                  # Microsoft Serialized Certificate Store
    ],
    'ebook': [
        # EPUB (Electronic Publication)
        "application/epub+zip",                           # EPUB

        # MOBI (Mobipocket)
        "application/x-mobipocket-ebook",                 # MOBI
        "application/x-mobi8-ebook",                      # MOBI (alternative MIME)

        # AZW (Amazon Kindle)
        "application/vnd.amazon.ebook",                   # AZW
        "application/vnd.amazon.mobi8-ebook",             # AZW3 (alternative MIME)

        # FB2 (FictionBook 2)
        "application/x-fictionbook+xml",                  # FB2
        "text/xml",                                       # FB2 (alternative MIME)

        # LIT (Microsoft Reader)
        "application/x-ms-reader",                        # LIT

        # CBR / CBZ (Comic Book Archive)
        "application/x-cbr",                              # CBR (Comic Book RAR)
        "application/x-cbz",                              # CBZ (Comic Book ZIP)

        # DJVU (DjVu Format)
        "image/vnd.djvu",                                 # DJVU

        # IBA (Apple iBooks Author)
        "application/vnd.apple.ibooks",                   # IBA

        # LRF (Sony Reader)
        "application/x-sony-bbeb",                        # LRF
        "application/x-sony-bbeb+xml",                    # LRX (alternative MIME)

        # PDB (Palm Media eBook)
        "application/vnd.palm",                           # PDB (Palm eBook)

        # KFX (Amazon Kindle Format 10)
        "application/vnd.amazon.kfx",                     # KFX
        "application/x-kindle8-ebook",                    # KFX (alternative MIME)

        # Other Formats
        "application/vnd.oasis.opendocument.text",        # ODT (OpenDocument Text)
        "application/vnd.openxmlformats-officedocument.wordprocessingml.document",  # DOCX (e-book use case)
    ],
    'script': [
        # Python
        "text/x-python",                                  # Python Script
        "application/x-python-code",                      # Compiled Python Script

        # JavaScript
        "application/javascript",                         # JavaScript
        "text/javascript",                                # JavaScript (alternative MIME)

        # TypeScript
        "application/x-typescript",                       # TypeScript

        # PHP
        "application/x-httpd-php",                        # PHP Script
        "text/x-php",                                     # PHP Script (alternative MIME)

        # Bash / Shell
        "application/x-sh",                               # Shell Script
        "text/x-shellscript",                             # Shell Script (alternative MIME)

        # Ruby
        "application/x-ruby",                             # Ruby Script
        "text/x-ruby",                                    # Ruby Script (alternative MIME)

        # Perl
        "application/x-perl",                             # Perl Script
        "text/x-perl",                                    # Perl Script (alternative MIME)

        # PowerShell
        "application/x-powershell",                       # PowerShell Script
        "text/x-powershell",                              # PowerShell Script (alternative MIME)

        # Lua
        "text/x-lua",                                     # Lua Script

        # Batch
        "application/x-msdos-program",                   # Batch Script
        "text/x-batch",                                   # Batch Script (alternative MIME)

        # Groovy
        "application/x-groovy",                           # Groovy Script
        "text/x-groovy",                                  # Groovy Script (alternative MIME)

        # Other Scripting Formats
        "application/x-java-jnlp-file",                   # JNLP (Java Network Launch Protocol)
        "application/x-tcl",                              # Tcl Script
        "text/x-tcl",                                     # Tcl Script (alternative MIME)
        "application/x-scala",                            # Scala Script
        "text/x-scala",                                   # Scala Script (alternative MIME)
    ],
    'virtual_disk_image': [
        # VMDK (VMware Virtual Disk)
        "application/x-vmdk",                             # VMDK

        # VDI (VirtualBox Disk Image)
        "application/x-virtualbox-vdi",                   # VDI

        # VHD / VHDX (Microsoft Virtual Hard Disk)
        "application/x-vhd",                              # VHD
        "application/x-vhdx",                             # VHDX

        # QCOW / QCOW2 (QEMU Copy-On-Write)
        "application/x-qemu-disk",                        # QCOW
        "application/x-qcow2",                            # QCOW2

        # RAW (Unformatted Disk Image)
        "application/x-raw-disk-image",                   # RAW
        "application/octet-stream",                       # RAW (alternative MIME)

        # ISO (Disk Image)
        "application/x-iso9660-image",                    # ISO

        # Parallels Disk Image
        "application/x-parallels-disk",                   # HDD (Parallels Disk Image)

        # Other Virtual Disk Formats
        "application/x-vmware-disk",                      # VMware Disk
        "application/x-virtual-disk",                     # Generic Virtual Disk
    ],
    'cad': [
        # DWG (AutoCAD Drawing)
        "application/acad",                               # DWG (legacy MIME type)
        "application/x-acad",                             # DWG (alternative MIME)
        "application/autocad_dwg",                        # DWG (alternative MIME)
        "application/dwg",                                # DWG (alternative MIME)
        "application/x-dwg",                              # DWG

        # DXF (Drawing Exchange Format)
        "image/vnd.dxf",                                  # DXF
        "application/dxf",                                # DXF (alternative MIME)
        "application/vnd.dxf",                            # DXF (alternative MIME)

        # DWF (Design Web Format)
        "application/vnd.dwf",                            # DWF
        "application/x-dwf",                              # DWF (alternative MIME)

        # STEP / STP (Standard for the Exchange of Product Data)
        "application/step",                               # STEP
        "application/x-step",                             # STEP (alternative MIME)
        "application/vnd.step",                           # STEP (alternative MIME)

        # IGES / IGS (Initial Graphics Exchange Specification)
        "application/iges",                               # IGES
        "application/x-iges",                             # IGES (alternative MIME)

        # STL (Stereolithography)
        "application/vnd.ms-pki.stl",                     # STL
        "application/sla",                                # STL (alternative MIME)
        "application/vnd.stl",                            # STL (alternative MIME)
        "model/stl",                                      # STL (alternative MIME)

        # 3MF (3D Manufacturing Format)
        "application/vnd.ms-package.3dmanufacturing-3dmodel+xml", # 3MF

        # OBJ (Wavefront Object)
        "application/x-tgif",                             # OBJ (alternative MIME)
        "application/vnd.obj",                            # OBJ (alternative MIME)
        "model/obj",                                      # OBJ (standard MIME)

        # Other CAD Formats
        "application/vnd.solidworks",                     # SolidWorks
        "application/x-proeng",                           # Pro/ENGINEER
        "application/vnd.bentley.microstation.dgn",       # MicroStation DGN
        "application/x-geometry",                         # Generic CAD geometry
    ],
}
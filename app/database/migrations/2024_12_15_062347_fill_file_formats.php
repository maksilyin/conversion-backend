<?php

use App\Models\FileCategory;
use App\Models\FileFormat;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    private $data = [
        ['name' => 'PNG', 'extension' => 'png', 'extended_name' => 'Portable Network Graphics', 'mime_type' => 'image/png', 'color' => '#03A9F4', 'category' => 'image'],
        ['name' => 'JPG', 'extension' => 'jpg', 'extended_name' => 'Joint Photographic Experts Group', 'mime_type' => 'image/jpeg', 'color' => '#FF9800', 'category' => 'image'],
        ['name' => 'AAI', 'extension' => 'aai', 'extended_name' => 'AAI Dune image', 'mime_type' => 'image/aai', 'color' => '#4CAF50', 'category' => 'image'],
        ['name' => 'ART', 'extension' => 'art', 'extended_name' => 'PFS: 1st Publisher Clip Art', 'mime_type' => 'image/x-jg', 'color' => '#9E9E9E', 'category' => 'image'],
        ['name' => 'ARW', 'extension' => 'arw', 'extended_name' => 'Sony Alpha Raw Image Format', 'mime_type' => 'image/x-sony-arw', 'color' => '#616161', 'category' => 'image'],
        ['name' => 'AVS', 'extension' => 'avs', 'extended_name' => 'AVS X image', 'mime_type' => 'image/x-avs', 'color' => '#8D6E63', 'category' => 'image'],
        ['name' => 'BGR', 'extension' => 'bgr', 'extended_name' => 'Raw blue, green, and red samples', 'mime_type' => 'image/x-bgr', 'color' => '#2196F3', 'category' => 'image'],
        ['name' => 'BGRA', 'extension' => 'bgra', 'extended_name' => 'Raw blue, green, red, and alpha samples', 'mime_type' => 'image/x-bgra', 'color' => '#03A9F4', 'category' => 'image'],
        ['name' => 'BMP', 'extension' => 'bmp', 'extended_name' => 'Microsoft Windows bitmap image', 'mime_type' => 'image/bmp', 'color' => '#1E88E5', 'category' => 'image'],
        ['name' => 'BMP2', 'extension' => 'bmp2', 'extended_name' => 'Microsoft Windows bitmap image (V2)', 'mime_type' => 'image/x-ms-bmp', 'color' => '#1565C0', 'category' => 'image'],
        ['name' => 'BMP3', 'extension' => 'bmp3', 'extended_name' => 'Microsoft Windows bitmap image (V3)', 'mime_type' => 'image/x-ms-bmp', 'color' => '#0D47A1', 'category' => 'image'],
        ['name' => 'CMYK', 'extension' => 'cmyk', 'extended_name' => 'Raw cyan, magenta, yellow, and black samples', 'mime_type' => 'image/x-cmyk', 'color' => '#00BCD4', 'category' => 'image'],
        ['name' => 'CR2', 'extension' => 'cr2', 'extended_name' => 'Canon Digital Camera Raw', 'mime_type' => 'image/x-canon-cr2', 'color' => '#880E4F', 'category' => 'image'],
        ['name' => 'CR3', 'extension' => 'cr3', 'extended_name' => 'Canon Digital Camera Raw', 'mime_type' => 'image/x-canon-cr3', 'color' => '#AD1457', 'category' => 'image'],
        ['name' => 'DDS', 'extension' => 'dds', 'extended_name' => 'Microsoft DirectDraw Surface', 'mime_type' => 'image/vnd.ms-dds', 'color' => '#0288D1', 'category' => 'image'],
        ['name' => 'DNG', 'extension' => 'dng', 'extended_name' => 'Digital Negative', 'mime_type' => 'image/x-adobe-dng', 'color' => '#607D8B', 'category' => 'image'],
        ['name' => 'DPX', 'extension' => 'dpx', 'extended_name' => 'SMPTE 268M-2003', 'mime_type' => 'image/x-dpx', 'color' => '#FFB300', 'category' => 'image'],
        ['name' => 'EXR', 'extension' => 'exr', 'extended_name' => 'High Dynamic Range', 'mime_type' => 'image/x-exr', 'color' => '#8BC34A', 'category' => 'image'],
        ['name' => 'FITS', 'extension' => 'fits', 'extended_name' => 'Flexible Image Transport System', 'mime_type' => 'image/fits', 'color' => '#1A237E', 'category' => 'image'],
        ['name' => 'GIF', 'extension' => 'gif', 'extended_name' => 'CompuServe graphics format', 'mime_type' => 'image/gif', 'color' => '#D81B60', 'category' => 'image'],
        ['name' => 'ICO', 'extension' => 'ico', 'extended_name' => 'Microsoft icon', 'mime_type' => 'image/vnd.microsoft.icon', 'color' => '#29B6F6', 'category' => 'image'],
        ['name' => 'JPEG', 'extension' => 'jpeg', 'extended_name' => 'Joint Photographic Experts Group', 'mime_type' => 'image/jpeg', 'color' => '#FF9800', 'category' => 'image'],
        ['name' => 'MIFF', 'extension' => 'miff', 'extended_name' => 'Magick Image File Format', 'mime_type' => 'image/x-miff', 'color' => '#BA68C8', 'category' => 'image'],
        ['name' => 'NEF', 'extension' => 'nef', 'extended_name' => 'Nikon Digital SLR Camera Raw', 'mime_type' => 'image/x-nikon-nef', 'color' => '#2E7D32', 'category' => 'image'],
        ['name' => 'ORF', 'extension' => 'orf', 'extended_name' => 'Olympus Digital Camera Raw', 'mime_type' => 'image/x-olympus-orf', 'color' => '#9E9D24', 'category' => 'image'],
        ['name' => 'PCX', 'extension' => 'pcx', 'extended_name' => 'IBM PC Paintbrush', 'mime_type' => 'image/x-pcx', 'color' => '#8D6E63', 'category' => 'image'],
        ['name' => 'PPM', 'extension' => 'ppm', 'extended_name' => 'Portable pixmap format', 'mime_type' => 'image/x-portable-pixmap', 'color' => '#E53935', 'category' => 'image'],
        ['name' => 'PSD', 'extension' => 'psd', 'extended_name' => 'Adobe Photoshop bitmap', 'mime_type' => 'image/vnd.adobe.photoshop', 'color' => '#3F51B5', 'category' => 'image'],
        ['name' => 'RAW', 'extension' => 'raw', 'extended_name' => 'Raw image data', 'mime_type' => 'image/x-raw', 'color' => '#FFFFFF', 'category' => 'image'],
        ['name' => 'TIFF', 'extension' => 'tiff', 'extended_name' => 'Tagged Image File Format', 'mime_type' => 'image/tiff', 'color' => '#FBC02D', 'category' => 'image'],
        ['name' => 'WEBP', 'extension' => 'webp', 'extended_name' => 'WebP Image Format', 'mime_type' => 'image/webp', 'color' => '#CDDC39', 'category' => 'image'],
        ['name' => 'XBM', 'extension' => 'xbm', 'extended_name' => 'X Windows system bitmap', 'mime_type' => 'image/x-xbitmap', 'color' => '#424242', 'category' => 'image'],
        ['name' => 'XCF', 'extension' => 'xcf', 'extended_name' => 'GIMP image', 'mime_type' => 'image/x-xcf', 'color' => '#9C27B0', 'category' => 'image'],
        ['name' => 'YUV', 'extension' => 'yuv', 'extended_name' => 'CCIR 601', 'mime_type' => 'image/x-yuv', 'color' => '#FDD835', 'category' => 'image'],
        ['name' => 'TGA', 'extension' => 'tga', 'extended_name' => 'Truevision Targa image', 'mime_type' => 'image/x-targa', 'color' => '#D84315', 'category' => 'image'],
        ['name' => 'TIFF64', 'extension' => 'tiff64', 'extended_name' => 'Tagged Image File Format (64-bit)', 'mime_type' => 'image/tiff', 'color' => '#FBC02D', 'category' => 'image'],
        ['name' => 'SUN', 'extension' => 'sun', 'extended_name' => 'SUN Rasterfile', 'mime_type' => 'image/x-sun-raster', 'color' => '#FFC107', 'category' => 'image'],
        ['name' => 'SVG', 'extension' => 'svg', 'extended_name' => 'Scalable Vector Graphics', 'mime_type' => 'image/svg+xml', 'color' => '#FFD54F', 'category' => 'vector'],
        ['name' => 'SVGZ', 'extension' => 'svgz', 'extended_name' => 'Compressed Scalable Vector Graphics', 'mime_type' => 'image/svg+xml', 'color' => '#FFB300', 'category' => 'vector'],
        ['name' => 'FAX', 'extension' => 'fax', 'extended_name' => 'Group 3 FAX', 'mime_type' => 'image/g3fax', 'color' => '#212121', 'category' => 'image'],
        ['name' => 'EPT3', 'extension' => 'ept3', 'extended_name' => 'Encapsulated PostScript Level III with TIFF preview', 'mime_type' => 'application/postscript', 'color' => '#FF6F00', 'category' => 'vector'],
        ['name' => 'RADIAL-GRADIENT', 'extension' => 'radial-gradient', 'extended_name' => 'Gradual radial passing from one shade to another', 'mime_type' => 'image/svg+xml', 'color' => '#B39DDB', 'category' => 'vector'],
        ['name' => 'PDF', 'extension' => 'pdf', 'extended_name' => 'Portable Document Format', 'mime_type' => 'application/pdf', 'color' => '#FF5722', 'category' => 'document'],
        ['name' => 'PDFA', 'extension' => 'pdfa', 'extended_name' => 'Portable Document Archive Format', 'mime_type' => 'application/pdf', 'color' => '#E64A19', 'category' => 'document'],
        ['name' => 'DOCX', 'extension' => 'docx', 'extended_name' => 'Microsoft Word Document', 'mime_type' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'color' => '#2196F3', 'category' => 'document'],
        ['name' => 'DOC', 'extension' => 'doc', 'extended_name' => 'Microsoft Word Document', 'mime_type' => 'application/msword', 'color' => '#1565C0', 'category' => 'document'],
        ['name' => 'XLSX', 'extension' => 'xlsx', 'extended_name' => 'Microsoft Excel Spreadsheet', 'mime_type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'color' => '#4CAF50', 'category' => 'spreadsheet'],
        ['name' => 'XLS', 'extension' => 'xls', 'extended_name' => 'Microsoft Excel Spreadsheet', 'mime_type' => 'application/vnd.ms-excel', 'color' => '#388E3C', 'category' => 'spreadsheet'],
        ['name' => 'PPTX', 'extension' => 'pptx', 'extended_name' => 'Microsoft PowerPoint Presentation', 'mime_type' => 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'color' => '#FF9800', 'category' => 'presentation'],
        ['name' => 'PPT', 'extension' => 'ppt', 'extended_name' => 'Microsoft PowerPoint Presentation', 'mime_type' => 'application/vnd.ms-powerpoint', 'color' => '#F57C00', 'category' => 'presentation'],
        ['name' => 'EPUB', 'extension' => 'epub', 'extended_name' => 'Electronic Publication', 'mime_type' => 'application/epub+zip', 'color' => '#673AB7', 'category' => 'document'],
        ['name' => 'RTF', 'extension' => 'rtf', 'extended_name' => 'Rich Text Format', 'mime_type' => 'application/rtf', 'color' => '#607D8B', 'category' => 'document'],
        ['name' => 'TXT', 'extension' => 'txt', 'extended_name' => 'Plain Text File', 'mime_type' => 'text/plain', 'color' => '#9E9E9E', 'category' => 'document'],
        ['name' => 'ODT', 'extension' => 'odt', 'extended_name' => 'OpenDocument Text Document', 'mime_type' => 'application/vnd.oasis.opendocument.text', 'color' => '#26A69A', 'category' => 'document'],
        ['name' => 'ODS', 'extension' => 'ods', 'extended_name' => 'OpenDocument Spreadsheet', 'mime_type' => 'application/vnd.oasis.opendocument.spreadsheet', 'color' => '#2E7D32', 'category' => 'spreadsheet'],
        ['name' => 'ODP', 'extension' => 'odp', 'extended_name' => 'OpenDocument Presentation', 'mime_type' => 'application/vnd.oasis.opendocument.presentation', 'color' => '#F9A825', 'category' => 'presentation'],
        ['name' => 'CSV', 'extension' => 'csv', 'extended_name' => 'Comma-Separated Values File', 'mime_type' => 'text/csv', 'color' => '#FFCCBC', 'category' => 'spreadsheet'],
        ['name' => 'MP4', 'extension' => 'mp4', 'extended_name' => 'MPEG-4 Video', 'mime_type' => 'video/mp4', 'color' => '#E53935', 'category' => 'video'],
        ['name' => 'AVI', 'extension' => 'avi', 'extended_name' => 'Audio Video Interleave', 'mime_type' => 'video/x-msvideo', 'color' => '#FF7043', 'category' => 'video'],
        ['name' => 'MKV', 'extension' => 'mkv', 'extended_name' => 'Matroska Video', 'mime_type' => 'video/x-matroska', 'color' => '#8D6E63', 'category' => 'video'],
        ['name' => 'MOV', 'extension' => 'mov', 'extended_name' => 'QuickTime Video', 'mime_type' => 'video/quicktime', 'color' => '#FFC107', 'category' => 'video'],
        ['name' => 'WMV', 'extension' => 'wmv', 'extended_name' => 'Windows Media Video', 'mime_type' => 'video/x-ms-wmv', 'color' => '#2196F3', 'category' => 'video'],
        ['name' => 'FLV', 'extension' => 'flv', 'extended_name' => 'Flash Video', 'mime_type' => 'video/x-flv', 'color' => '#0097A7', 'category' => 'video'],
        ['name' => 'WEBM', 'extension' => 'webm', 'extended_name' => 'WebM Video', 'mime_type' => 'video/webm', 'color' => '#4CAF50', 'category' => 'video'],
        ['name' => 'MP3', 'extension' => 'mp3', 'extended_name' => 'MPEG Audio Layer III', 'mime_type' => 'audio/mpeg', 'color' => '#FF9800', 'category' => 'audio'],
        ['name' => 'WAV', 'extension' => 'wav', 'extended_name' => 'Waveform Audio File Format', 'mime_type' => 'audio/wav', 'color' => '#2196F3', 'category' => 'audio'],
        ['name' => 'OGG', 'extension' => 'ogg', 'extended_name' => 'Ogg Vorbis Audio', 'mime_type' => 'audio/ogg', 'color' => '#4CAF50', 'category' => 'audio'],
        ['name' => 'FLAC', 'extension' => 'flac', 'extended_name' => 'Free Lossless Audio Codec', 'mime_type' => 'audio/flac', 'color' => '#8D6E63', 'category' => 'audio'],
        ['name' => 'AAC', 'extension' => 'aac', 'extended_name' => 'Advanced Audio Codec', 'mime_type' => 'audio/aac', 'color' => '#FF5722', 'category' => 'audio'],
        ['name' => 'AIFF', 'extension' => 'aiff', 'extended_name' => 'Audio Interchange File Format', 'mime_type' => 'audio/aiff', 'color' => '#FF7043', 'category' => 'audio'],
        ['name' => 'M4A', 'extension' => 'm4a', 'extended_name' => 'MPEG-4 Audio', 'mime_type' => 'audio/mp4', 'color' => '#E53935', 'category' => 'audio'],
        ['name' => 'ZIP', 'extension' => 'zip', 'extended_name' => 'ZIP Archive', 'mime_type' => 'application/zip', 'color' => '#FFEB3B', 'category' => 'archive'],
        ['name' => 'RAR', 'extension' => 'rar', 'extended_name' => 'RAR Archive', 'mime_type' => 'application/vnd.rar', 'color' => '#FFC107', 'category' => 'archive'],
        ['name' => '7Z', 'extension' => '7z', 'extended_name' => '7-Zip Archive', 'mime_type' => 'application/x-7z-compressed', 'color' => '#FFD740', 'category' => 'archive'],
        ['name' => 'TAR', 'extension' => 'tar', 'extended_name' => 'Tape Archive', 'mime_type' => 'application/x-tar', 'color' => '#FF9800', 'category' => 'archive'],
        ['name' => 'GZ', 'extension' => 'gz', 'extended_name' => 'GZIP Archive', 'mime_type' => 'application/gzip', 'color' => '#FF7043', 'category' => 'archive'],
        ['name' => 'TTF', 'extension' => 'ttf', 'extended_name' => 'TrueType Font', 'mime_type' => 'font/ttf', 'color' => '#673AB7', 'category' => 'font'],
        ['name' => 'OTF', 'extension' => 'otf', 'extended_name' => 'OpenType Font', 'mime_type' => 'font/otf', 'color' => '#512DA8', 'category' => 'font'],
        ['name' => 'WOFF', 'extension' => 'woff', 'extended_name' => 'Web Open Font Format', 'mime_type' => 'font/woff', 'color' => '#3F51B5', 'category' => 'font'],
        ['name' => 'WOFF2', 'extension' => 'woff2', 'extended_name' => 'Web Open Font Format 2', 'mime_type' => 'font/woff2', 'color' => '#303F9F', 'category' => 'font'],
    ];
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $categories = FileCategory::pluck('id', 'slug')->toArray();

        foreach ($this->data as $formatData) {
            $categoryId = $categories[$formatData['category']] ?? null;
            FileFormat::firstOrCreate(
                ['extension' => $formatData['extension']],
                [
                    'name' => $formatData['name'],
                    'extended_name' => $formatData['extended_name'],
                    'mime_type' => $formatData['mime_type'],
                    'color' => $formatData['color'],
                    'category_id' => $categoryId,
                ]
            );
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('file_formats', function (Blueprint $table) {
            //
        });
    }
};

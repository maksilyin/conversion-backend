<?php

namespace App\Http\Controllers;

use App\Http\Resources\FileFormatDetailResource;
use App\Models\FileCategory;
use App\Models\FileFormat;
use Illuminate\Http\Request;
use App\Http\Resources\FileCategoryResource;

class FileFormatsController extends Controller
{
    public function formats()
    {
        $fileCategories = FileCategory::select('id', 'name', 'slug', 'icon', 'icon_image')
            ->with([
                'translations',
                'formats:id,name,extension,category_id,icon,icon_image,mime_type,extended_name',
                'formats.convertible:id,name,extension',
                'formats.convertibleCategory:id,slug',
            ])
            ->get();

        return FileCategoryResource::collection($fileCategories)->toArray(request());
    }

    public function show($format)
    {
        $fileFormat = FileFormat::where('extension', $format)
            ->where('active', 1)
            ->with(['category:id,name,slug', 'convertible:id,name,extension', 'convertibleCategory:id,slug'])
            ->firstOrFail();

        return FileFormatDetailResource::make($fileFormat)->toArray(request());
    }

    public function fileType($type)
    {
        $fileCategory = FileCategory::select('id', 'name', 'slug', 'icon', 'icon_image')
            ->where('slug', $type)
            ->with([
                'translations',
                'formats:id,name,extension,category_id,icon,icon_image,mime_type,extended_name',
                'formats.convertible:id,name,extension',
                'formats.convertibleCategory:id,slug',
            ])
            ->firstOrFail();

        return FileCategoryResource::make($fileCategory)->toArray(request());
    }
}

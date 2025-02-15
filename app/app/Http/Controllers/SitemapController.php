<?php

namespace App\Http\Controllers;

use App\Helpers\LocaleHelper;
use App\Http\Resources\FileCategoryResource;
use App\Models\FileCategory;
use App\Models\FileFormat;
use App\Models\Language;
use App\Models\Page;
use Illuminate\Http\JsonResponse;

class SitemapController extends Controller
{
    public function index(): JsonResponse
    {
        $result = [];
        $baseConvert = '/convert/';
        $base = '/';

        $fileTypes = FileCategory::select('id', 'slug')
            ->with([
                'translations',
                'formats:id,extension,category_id,active',
                'formats.convertible:id,extension,active',
                'formats.convertibleCategory:id,slug',
            ])
            ->orderBy('id', 'asc')
            ->get()
            ->keyBy('slug')
            ->toArray();

        $pages = Page::where('active', 1)
            ->select('id', 'url')
            ->get();

        $locales = LocaleHelper::getActiveLocales();

        foreach ($fileTypes as $fileType) {
            $url = $baseConvert.$fileType['slug'];
            $result[] = [
                'url' => $url,
            ];

            foreach ($fileType['formats'] as $format) {
                if ($format['active']) {
                    $url = $baseConvert . $format['extension'];
                    $result[] = [
                        'url' => $url,
                    ];
                }

                if ($format['convertible']) {
                    foreach ($format['convertible'] as $convertibleFormat) {
                        if ($convertibleFormat['active']) {
                            $url = $baseConvert . $format['extension'] . '-' . $convertibleFormat['extension'];
                            $result[] = [
                                'url' => $url,
                            ];
                        }
                    }
                }

                if ($format['convertible_category']) {
                    foreach ($format['convertible_category'] as $convertibleType) {
                        if (isset($fileTypes[$convertibleType['slug']])) {
                            foreach ($fileTypes[$convertibleType['slug']]['formats'] as $format2) {
                                if ($format2['active'] && $format['extension'] !== $format2['extension']) {
                                    $url = $baseConvert . $format['extension'] . '-' . $format2['extension'];
                                    $result[] = [
                                        'url' => $url,
                                    ];
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($pages as $page) {
            $result[] = [
                'url' => $page->url,
            ];
        }

        $defaultLocale = app()->getLocale();

        $resultLocales = [];

        foreach ($locales as $locale) {
            if ($locale == $defaultLocale) {
                continue;
            }

            foreach ($result as $resultItem) {
                $resultLocales[] = [
                    'url' => '/'.$locale.$resultItem['url']
                ];
            }
        }

        $result = array_merge($result, $resultLocales);

        return response()->json($result);
    }
}

<?php

namespace App\Services;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostTranslation;
use App\Models\Translation;
use Carbon\Carbon;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class PostService
{
    private $locale;
    private $defaultLocale;
    /**
     * Create a new class instance.
     */
    public function __construct()
    {
        $this->locale = app()->getLocale();
        $this->defaultLocale = config('app.default_locale');
    }

    public function getPosts($perPage = 0): array
    {
        $posts = $this->getPostsQuery($perPage);

        return [
            'data' => $posts->getCollection()->transform(function ($post) {
                return $this->getNormalizePost($post);
            }),
            'pagination' => [
                'total' => $posts->lastPage(),
                'current' => $posts->currentPage(),
            ]
        ];
    }

    public function getPost($slug): ?array
    {
        $locale = $this->locale;
        $defaultLocale = $this->defaultLocale;

        $post = Post::with(['translations', 'seo', 'categories', 'tags'])
            ->where('active', 1)
            ->where('slug', $slug)
            ->whereDate('published_at', '<=', now())
            ->when($locale !== $defaultLocale, function ($query) use ($locale) {
                $query->whereHas('translations', function ($query) use ($locale) {
                    $query->where('locale', $locale);
                });
            })
            ->first();

        return $post ? $this->getNormalizePost($post, true) : null;
    }

    public function getPostsByCategory($slug, $perPage = 0): array
    {
        $category = Category::where('slug', $slug)->firstOrFail();
        $posts = $this->getPostsQuery($perPage, $category);

        return [
            'data' => $posts->getCollection()->transform(function ($post) {
                return $this->getNormalizePost($post);
            }),
            'pagination' => [
                'total' => $posts->lastPage(),
                'current' => $posts->currentPage(),
            ]
        ];
    }

    public function getPostsByTags(array $tags, $perPage = 0)
    {
        $posts = $this->getPostsQuery($perPage, null, $tags);

        return $posts->getCollection()->transform(function ($post) {
            return $this->getNormalizePost($post);
        });
    }

    public function getCategory($slug): array
    {
        $category = Category::where('slug', $slug)->with(['translations', 'seo'])->firstOrFail();

        return [
            'id' => $category->id,
            'name' => $category->getTranslate('name'),
            'slug' => $category->slug,
            'color' => $category->color,
            'seo' => [
                'title' => $category->metaTags ? $category->metaTags->meta_title : null,
                'description' => $category->metaTags ? $category->metaTags->meta_description : null
            ]
        ];
    }

    protected function getPostsQuery($perPage = 0, $category = null, $tags = []): LengthAwarePaginator
    {
        $locale = $this->locale;
        $defaultLocale = $this->defaultLocale;

        $query = Post::with([
            'translations',
            'categories' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
            'tags' => function ($query) {
                $query->select('id', 'name', 'slug');
            },
        ])
        ->where('active', 1)
        ->whereDate('published_at', '<=', now())
        ->when($category, function ($query) use ($category) {
            $query->whereHas('categories', function ($query) use ($category) {
                $query->where('id', $category->id);
            });
        })
        ->when(!empty($tags), function ($query) use ($tags) {
            $query->whereHas('tags', function ($query) use ($tags) {
                $query->whereIn('slug', $tags);
            });
        })
        ->when($locale !== $defaultLocale, function ($query) use ($locale) {
            $query->whereHas('translations', function ($query) use ($locale) {
                $query->where('locale', $locale);
            });
        })
        ->orderBy('published_at', 'desc');

        return $query->paginate($perPage);
    }

    public function getSearch($query, $perPage = 10)
    {
        $locale = $this->locale;
        $defaultLocale = $this->defaultLocale;
        $now = Carbon::now();

        $data = PostTranslation::with('post')
            ->where('locale', $locale)
            ->where(function ($q) use ($query) {
                $q->where('title', 'like', "%{$query}%")
                    ->orWhere('excerpt', 'like', "%{$query}%")
                    ->orWhere('content', 'like', "%{$query}%");
            })
            ->whereHas('post', function ($q) use ($now) {
                $q->where('active', true)
                    ->where('published_at', '<=', $now);
            })
            ->paginate($perPage);

        if ($data->isEmpty() && $locale === $defaultLocale) {
            $data = Post::where('active', true)
                ->where('published_at', '<=', $now)
                ->where(function ($q) use ($query) {
                    $q->where('title', 'like', "%{$query}%")
                        ->orWhere('excerpt', 'like', "%{$query}%")
                        ->orWhere('content', 'like', "%{$query}%");
                })
                ->paginate($perPage);
        }

        $normalizedData = $data->getCollection()->map(function ($item) {
            if ($item instanceof PostTranslation) {
                return $this->getNormalizePost($item->post);
            }

            return $this->getNormalizePost($item);
        });

        return [
            'data' => $normalizedData,
            'pagination' => [
                'total' => $data->lastPage(),
                'current' => $data->currentPage(),
            ]
        ];
    }

    public function getNormalizePost($post, $isDetail = false): array
    {
        $result = [
            'id' => $post->id,
            'slug' => $post->slug,
            'previewImage' => $post->preview_image,
            'readTime' => $post->read_time,
            'publishedAt' => Carbon::parse($post->published_at)->format('d.m.Y'),
            'title' => $post->post_title ?? $post->title,
            'previewText' => $post->preview_text ?? $post->excerpt,
            'locale' => $post->locale,
            'categories' => $post->categories()
                ->get()
                ->map(function ($category) {
                    return [
                        'id' => $category->id,
                        'name' => $category->getTranslate('name'),
                        'slug' => $category->slug,
                        'color' => $category->color,
                    ];
                })
                ->toArray(),
            'tags' => $post->tags()
                ->get()
                ->map(function ($tag) {
                    return [
                        'id' => $tag->id,
                        'name' => $tag->getTranslate('name'),
                        'slug' => $tag->slug,
                    ];
                })
                ->toArray(),
        ];

        if ($isDetail) {
            $result['detailText'] = $post->detail_text ?? $post->content;
            $result['detailImage'] = $post->detail_image;

            $result['seo'] = [
                'title' =>  $post->metaTags ? $post->metaTags->meta_title : null,
                'description' => $post->metaTags ? $post->metaTags->meta_description : null,
            ];
        }

        return $result;
    }
}

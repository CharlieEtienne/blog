<?php

namespace App\Support;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class SlugGenerator
{
    /**
     * Generate a unique slug from the title
     *
     * @param  class-string<Model>  $modelClass
     * @param  string|null  $title
     * @param  Model|null  $ignoreRecord
     *
     * @return string
     */
    public static function unique(string $modelClass, ?string $title, ?Model $ignoreRecord = null): string
    {
        $baseSlug = Str::slug($title);
        $slug = $baseSlug;
        $count = 1;

        while (
            $modelClass::query()
                ->where('slug', $slug)
                ->when($ignoreRecord, fn ($query) => $query->whereKeyNot($ignoreRecord->getKey()))
                ->exists()
        ) {
            $slug = $baseSlug . '-' . $count++;
        }

        return $slug;
    }

    /**
     * Optimistic slug generation with JS
     *
     * @param  string  $fieldName
     *
     * @return string
     * @noinspection JSUnresolvedReference
     */
    public static function slugifyWithJs(string $fieldName = 'slug'): string
    {
        return <<<JS
            const slug = (\$state ?? '')
                .normalize('NFD')
                .replace(/[\u0300-\u036f]/g, '')
                .toLowerCase()
                .trim()
                .replace(/[^a-z0-9]+/g, '-')
                .replace(/^-+|-+$/g, '');
            \$set('$fieldName', slug);
        JS;
    }
}

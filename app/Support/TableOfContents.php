<?php

namespace App\Support;

use Illuminate\Support\Str;

class TableOfContents
{
    protected array $toc = [];

    protected string $view = 'components.table-of-contents';

    public static function generate(array|string|null $doc): self {

        $instance = new self();

        if (empty($doc) || is_string($doc)) {
            return $instance;
        }

        $blocks = $doc['content'] ?? [];

        foreach ($blocks as $block) {
            if ($block['type'] === 'heading') {
                $level = $block['attrs']['level'] ?? 1;
                $text = collect($block['content'] ?? [])
                    ->filter(fn($part) => $part['type'] === 'text')
                    ->pluck('text')
                    ->implode('');

                $instance->toc[] = [
                    'level' => $level,
                    'text' => $text,
                    'id' => Str::slug($text),
                ];
            }
        }

        return $instance;
    }

    /**
     * Render the table of contents as HTML using the component
     *
     * @return string
     */
    public function render(): string
    {
        return view($this->view, ['toc' => $this->toc])->render();
    }

}

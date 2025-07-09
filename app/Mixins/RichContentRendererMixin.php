<?php

namespace App\Mixins;

use Closure;
use League\HTMLToMarkdown\HtmlConverter;
use Tiptap\Editor;

/**
 * @author awcodes
 */
class RichContentRendererMixin
{
    public function toHtmlWithHeadingLinks(): Closure
    {
        return function (int $maxDepth = 3, bool $wrapHeadings = false) {
            $editor = $this->getEditor();

            $this->processCustomBlocks($editor);
            $this->processFileAttachments($editor);
            $this->processMergeTags($editor);
            $this->processHeadings($editor, $maxDepth, $wrapHeadings);

            return $editor->getHtml();
        };
    }

    public function toMarkdown(): Closure
    {
        return function (bool $toc = false, int $maxDepth = 3, ?array $options = []) {
            $editor = $this->getEditor();

            $this->processCustomBlocks($editor);
            $this->processFileAttachments($editor);
            $this->processMergeTags($editor);

            if ($toc) {
                $this->processHeadings($editor, $maxDepth);
            }

            return (new HtmlConverter($options))->convert(
                $editor->getHtml()
            );
        };
    }

    public function toToc(): Closure
    {
        return function (int $maxDepth = 3, bool $asArray = false) {
            $editor = $this->getEditor();

            $this->processCustomBlocks($editor);
            $this->processFileAttachments($editor);
            $this->processMergeTags($editor);

            $headings = $this->getHeadings($editor, $maxDepth);

            return $asArray ?
                $this->generateTocArray($headings) :
                $this->generateTocHtml($headings, $headings[0]['level']);
        };
    }

    public function toText(): Closure
    {
        return function () {
            $editor = $this->getEditor();

            $this->processCustomBlocks($editor);
            $this->processFileAttachments($editor);
            $this->processMergeTags($editor);

            return $editor->getText();
        };
    }

    public function processHeadings(): Closure
    {
        return function (Editor $editor, int $maxDepth = 3, bool $wrapHeadings = false) {
            $editor->descendants(function (&$node) use ($maxDepth, $wrapHeadings): void {
                if ($node->type !== 'heading') {
                    return;
                }

                if ($node->attrs->level > $maxDepth) {
                    return;
                }

                if (! property_exists($node->attrs, 'id') || $node->attrs->id === null) {
                    $node->attrs->id = str(collect($node->content)->map(fn ($node) => $node->text ?? null)->implode(' '))->slug()->toString();
                }

                if ($wrapHeadings) {
                    $text = str(collect($node->content)->map(fn ($node) => $node->text ?? null)->implode(' '))->toString();

                    $node->content = [
                        (object) [
                            'type' => 'text',
                            'marks' => [
                                [
                                    'type' => 'link',
                                    'attrs' => [
                                        'href' => '#' . $node->attrs->id,
                                        'class' => 'toc-link',
                                    ],
                                ],
                            ],
                            'text' => $text,
                        ],
                    ];
                } else {
                    array_unshift($node->content, (object) [
                        'type' => 'text',
                        'text' => '#',
                        'marks' => [
                            [
                                'type' => 'link',
                                'attrs' => [
                                    'href' => '#' . $node->attrs->id,
                                    'class' => 'toc-link',
                                ],
                            ],
                        ],
                    ]);
                }
            });
        };
    }

    public function getHeadings(): Closure
    {
        return function (Editor $editor, int $maxDepth = 3) {
            $headings = [];

            $editor->descendants(function (&$node) use (&$headings, $maxDepth): void {
                if ($node->type !== 'heading') {
                    return;
                }

                if ($node->attrs->level > $maxDepth) {
                    return;
                }

                $text = collect($node->content)->map(fn ($node): mixed => $node->text ?? null)->implode(' ');

                if (! isset($node->attrs->id)) {
                    $node->attrs->id = str($text)->slug()->toString();
                }

                $headings[] = [
                    'level' => $node->attrs->level,
                    'id' => $node->attrs->id,
                    'text' => $text,
                ];
            });

            return $headings;
        };
    }

    public function generateTocArray(): Closure
    {
        return function (array &$headings, int $parentLevel = 0) {
            $result = [];

            foreach ($headings as $key => &$value) {
                $currentLevel = $value['level'];
                $nextLevel = $headings[$key + 1]['level'] ?? 0;

                if ($parentLevel >= $currentLevel) {
                    break;
                }

                unset($headings[$key]);

                $heading = [
                    'id' => $value['id'],
                    'text' => $value['text'],
                    'depth' => $currentLevel,
                ];

                if ($nextLevel > $currentLevel) {
                    $heading['subs'] = $this->generateTocArray($headings, $currentLevel);
                    unset($headings[$key + 1]);
                }

                $result[] = $heading;

            }

            return $result;
        };
    }

    public function generateTocHtml(): Closure
    {
        return function (array $headings, int $parentLevel = 0) {
            $result = '<ul>';
            $prev = $parentLevel;

            foreach ($headings as $item) {
                $prev <= $item['level'] ?: $result .= str_repeat('</ul>', $prev - $item['level']);
                $prev >= $item['level'] ?: $result .= '<ul>';

                $result .= '<li><a href="#' . $item['id'] . '">' . $item['text'] . '</a></li>';

                $prev = $item['level'];
            }

            return $result . '</ul>';
        };
    }
}

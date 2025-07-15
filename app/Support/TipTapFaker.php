<?php

namespace App\Support;

class TipTapFaker
{
    public static function content(...$blocks): array
    {
        return [
            "en" => [
                "type" => "doc",
                "content" => [
                    ...$blocks,
                ],
            ]
        ];
    }
    public static function paragraph(): array
    {
        return [
            "type" => "paragraph",
            "attrs" => ["textAlign" => "start"],
            "content" => [["type" => "text", "text" => fake()->paragraph()]],
        ];
    }

    public static function h2(): array
    {
        return [
            "type" => "heading",
            "content" => [["type" => "text", "text" => fake()->sentence()]],
            "attrs" => ["textAlign" => "start", "level" => 2],
        ];
    }

    public static function h3(): array
    {
        return [
            "type" => "heading",
            "content" => [["type" => "text", "text" => fake()->sentence()]],
            "attrs" => ["textAlign" => "start", "level" => 3],
        ];
    }

    public static function codeCustomBlock(): array
    {
        return [
            "type" => "customBlock",
            "attrs" => [
                "config" => [
                    "code" => <<<'PHP'
public static function content(...$blocks): array
{
    return [
        "type" => "doc",
        "content" => [
            ...$blocks,
        ],
    ];
}
PHP,
                    "language" => "php"
                ],
                "id" => "code"
            ]
        ];
    }

}

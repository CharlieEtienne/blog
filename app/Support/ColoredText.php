<?php

namespace App\Support;

class ColoredText
{

    /**
     * Output colored pieces of text when wrapped between a special character
     */
    public static function get(string $text, string $separator = "|"): string
    {
        return str($text)
            ->replaceMatches(
                sprintf(
                    "/%s(.*?)%s/",
                    preg_quote($separator, '/'),
                    preg_quote($separator, '/')
                ),
                '<span class="colored">$1</span>'
            )
            ->toString();
    }

}

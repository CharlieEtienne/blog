<?php

namespace App\Support;

class Icons
{
    public static function getHeroicon(string $name, bool $isOutlined = false, string $class = ''): string
    {
        $iconName = 'heroicon-' . ($isOutlined ? 'o' : 's') . '-' . $name;
        return svg($iconName, $class)->toHtml();
    }
}

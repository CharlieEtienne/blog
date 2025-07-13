<?php

namespace App\Enums;

enum Analytics: string
{
    case HOME = 'home';
    case POSTS = 'posts';
    case MAIN_MENU = 'main-menu';
    case FOOTER_MENU = 'footer-menu';
    case DROPDOWN_MENU = 'dropdown-menu';

    public function getTitle(): string
    {
        return str($this->value)->replace('-', ' ')->title()->toString();
    }
}

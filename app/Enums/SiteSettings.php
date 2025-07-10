<?php

namespace App\Enums;

use Rawilk\Settings\Facades\Settings;

enum SiteSettings: string
{
    case POST_DEFAULT_IMAGE = 'post_default_image';
    case SITE_LOGO = 'site_logo';
    case FAVICON = 'favicon';
    case SITE_NAME = 'site_name';
    case DISPLAY_SITE_NAME = 'display_site_name';
    case PRIMARY_COLOR = 'primary_color';
    case HEADING_FONT = 'heading_font';
    case BODY_FONT = 'body_font';
    case CODE_FONT = 'code_font';
    case CODE_THEME = 'code_theme';
    case HERO_TITLE = 'hero_title';
    case HERO_SUBTITLE = 'hero_subtitle';
    case HERO_IMAGE = 'hero_image';
    case HERO_IMAGE_HEIGHT = 'hero_image_height';
    case HERO_IMAGE_FULL_WIDTH = 'hero_image_full_width';
    case ABOUT_IMAGE = 'about_image';
    case ABOUT_TEXT = 'about_text';
    case ABOUT_TITLE = 'about_title';
    case ABOUT_IMAGE_CIRCULAR = 'about_image_circular';
    case ABOUT_IMAGE_WIDTH = 'about_image_width';
    case ABOUT_IMAGE_HEIGHT = 'about_image_height';
    case CONTACT_EMAIL = 'contact_email';
    case FOOTER_TEXT = 'footer_text';
    case COPYRIGHT_TEXT = 'copyright_text';
    case MAIN_MENU = 'main_menu';
    case MAIN_MENU_MORE = 'main_menu_more';
    case FOOTER_MENU = 'footer_menu';
    case PERMALINKS = 'permalinks';

    /**
     * Get the default value for the setting
     *
     * @noinspection PhpDuplicateMatchArmBodyInspection
     */
    public function getDefaultValue(): string|array|null
    {
        return match ($this) {
            self::POST_DEFAULT_IMAGE => null,
            self::SITE_LOGO => null,
            self::FAVICON => null,
            self::SITE_NAME => config('app.name'),
            self::DISPLAY_SITE_NAME => true,
            self::PRIMARY_COLOR => '#6366f1',
            self::HEADING_FONT => 'Hanken Grotesk',
            self::BODY_FONT => 'Hanken Grotesk',
            self::CODE_FONT => 'JetBrains Mono',
            self::CODE_THEME => 'catppuccin-macchiato',
            self::HERO_TITLE => "Welcome to my |personal| blog",
            self::HERO_SUBTITLE => "A place to share my thoughts",
            self::HERO_IMAGE => null,
            self::HERO_IMAGE_HEIGHT => 350,
            self::HERO_IMAGE_FULL_WIDTH => true,
            self::ABOUT_IMAGE => null,
            self::ABOUT_TEXT => null,
            self::ABOUT_TITLE => "About me",
            self::ABOUT_IMAGE_CIRCULAR => true,
            self::ABOUT_IMAGE_WIDTH => 100,
            self::ABOUT_IMAGE_HEIGHT => 100,
            self::CONTACT_EMAIL => '',
            self::FOOTER_TEXT => "Made with ❤️ by me",
            self::COPYRIGHT_TEXT => "©{year}",
            self::MAIN_MENU => [
                [
                    "icon" => "o-home",
                    "name" => "Home",
                    "url" => "/",
                    "open_in_new_tab" => false,
                    "page" => "home",
                ],
                [
                    "icon" => "o-newspaper",
                    "name" => "Blog",
                    "url" => "/blog",
                    "open_in_new_tab" => false,
                    "page" => "blog",
                ],
            ],
            self::MAIN_MENU_MORE => [
                [
                    "type" => "item",
                    "data" => [
                        "icon" => "iconoir-chat-bubble-question",
                        "page" => "custom",
                        "name" => "About",
                        "url" => "/#about",
                        "open_in_new_tab" => false,
                    ],
                ],
                [
                    "type" => "item",
                    "data" => [
                        "icon" => "iconoir-mail-out",
                        "page" => "custom",
                        "name" => "Contact",
                        "url" => "mailto:hello@example.com",
                        "open_in_new_tab" => false,
                    ],
                ],
                [
                    "type" => "divider",
                    "data" => [
                        "label" => "Source Code",
                    ],
                ],
                [
                    "type" => "item",
                    "data" => [
                        "icon" => "iconoir-git-fork",
                        "page" => "custom",
                        "name" => "Source code",
                        "url" => "https://github.com/charlieetienne/blog",
                        "open_in_new_tab" => true,
                    ],
                ],
                [
                    "type" => "divider",
                    "data" => [
                        "label" => "Follow me",
                    ],
                ],
                [
                    "type" => "item",
                    "data" => [
                        "icon" => "iconoir-github",
                        "page" => "custom",
                        "name" => "GitHub",
                        "url" => "https://github.com/charlieetienne",
                        "open_in_new_tab" => true,
                    ],
                ],
                [
                    "type" => "item",
                    "data" => [
                        "icon" => "iconoir-x",
                        "page" => "custom",
                        "name" => "X",
                        "url" => "https://x.com/charlieetienne",
                        "open_in_new_tab" => true,
                    ],
                ],
            ],
            self::FOOTER_MENU => [
                [
                    "name" => "Home",
                    "url" => "/",
                    "open_in_new_tab" => false,
                    "page" => "home",
                ],
                [
                    "name" => "Blog",
                    "url" => "/blog",
                    "open_in_new_tab" => false,
                    "page" => "blog",
                ],
                [
                    "name" => "About",
                    "url" => "/#about",
                    "open_in_new_tab" => false,
                    "page" => "custom",
                ],
                [
                    "name" => "Contact",
                    "url" => "mailto:" . SiteSettings::CONTACT_EMAIL->get(),
                    "open_in_new_tab" => false,
                    "page" => "custom",
                ],
            ],
            self::PERMALINKS => collect(MainPages::cases())->mapWithKeys(fn(MainPages $page) => [$page->value => $page->getDefaultSlug()])->toArray(),
        };
    }

    public function get(): mixed
    {
        if (app()->runningInConsole()){
            return $this->getDefaultValue();
        }
        return Settings::get($this->value, $this->getDefaultValue());
    }

    public function set(mixed $value): void
    {
        Settings::set($this->value, $value);
    }
}

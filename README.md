# TALL Stack Blog App

A fully-featured, configurable blog.

Clone the project, log in as admin, tweak some settings and push to prod ☁️.

It's fully open source. 

## Stack

- Laravel v12
- Livewire
- AlpineJS
- TailwindCSS v4
- Filament v4

## Features

### Full Admin Panel (Filament) with:
  - [x] Blog posts
  - [x] Categories
  - [x] Tags
  - [x] Menu Builder
  - [x] Analytics with panphp/pan
  - [x] Multilingual
  - [ ] Comments
  - [ ] CMS Pages
  - [ ] Portfolio

### Front end (TALL Stack), with:
  - [x] Homepage
  - [x] About Section
  - [x] Blog posts with code blocks, table of contents and more
  - [x] Categories
  - [x] Tags
  - [x] A modern and beautiful theme with dark mode

### You'll be able to configure a lot of options from the Settings page:
  - [x] Post default image
  - [x] Site logo
  - [x] Favicon
  - [x] Site name
  - [x] Display site name
  - [x] Primary color
  - [x] Heading font
  - [x] Body font
  - [x] Code font
  - [x] Code theme
  - [x] Hero title
  - [x] Hero subtitle
  - [x] Hero image
  - [x] Hero image height
  - [x] Hero image full width
  - [x] About image
  - [x] About text
  - [x] About title
  - [x] About image circular
  - [x] About image width
  - [x] About image height
  - [x] Contact email
  - [x] Footer text
  - [x] Copyright text
  - ...

### Misc

Fonts are loaded through [fonts.bunny.net](https://fonts.bunny.net/) so your blog is GDPR friendly.

## Installation

### 1. Clone the repo

```bash
git clone https://github.com/charlieetienne/blog.git
```

### 2. Install dependencies

```bash
composer install
```

### 3. Run the installer

You will be prompted for several configuration options.

```bash
php artisan blog:install
```

### 4. Enjoy your new blog!

## Testing

You can run the test suite with the following command:

```bash
php artisan test --parallel
```

## Contribute

Contributions are welcome!

## Credits

Some parts of this blog are heavily inspired by https://github.com/benjamincrozat/blog-v5.

Also, big thanks to @awcodes for his help on TipTap plugins and table of contents!

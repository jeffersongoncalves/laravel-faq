# Laravel FAQ

[![Latest Version on Packagist](https://img.shields.io/packagist/v/jeffersongoncalves/laravel-faq.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-faq)
[![GitHub Tests Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-faq/tests.yml?branch=main&label=tests&style=flat-square)](https://github.com/jeffersongoncalves/laravel-faq/actions?query=workflow%3ATests+branch%3Amain)
[![GitHub Code Style Action Status](https://img.shields.io/github/actions/workflow/status/jeffersongoncalves/laravel-faq/fix-php-code-style-issues.yml?branch=main&label=code%20style&style=flat-square)](https://github.com/jeffersongoncalves/laravel-faq/actions?query=workflow%3A%22Fix+PHP+code+style+issues%22+branch%3Amain)
[![Total Downloads](https://img.shields.io/packagist/dt/jeffersongoncalves/laravel-faq.svg?style=flat-square)](https://packagist.org/packages/jeffersongoncalves/laravel-faq)
[![License](https://img.shields.io/packagist/l/jeffersongoncalves/laravel-faq.svg?style=flat-square)](LICENSE.md)

A Laravel package for managing FAQs (Frequently Asked Questions), with translatable categories, questions, and answers powered by [spatie/laravel-translatable](https://github.com/spatie/laravel-translatable).

## Features

- **FAQ Categories** — Organize FAQs into categories with a name, slug, order, and active flag
- **FAQs** — Question/answer pairs, optionally attached to a category (a FAQ can exist without one)
- **Translatable Content** — Category names, questions, and answers are translatable via `spatie/laravel-translatable`, with automatic fallback to the app's fallback locale
- **Ordering & Activation** — `ordered()` and `active()` query scopes on both models
- **Configurable Table Names** — Override the `faq_categories` / `faqs` table names via config
- **Configurable Locales** — Mirrors `app.available_locales` (or the app locale) to describe supported translation locales

## Requirements

- PHP 8.2+
- Laravel 11+

## Installation

You can install the package via composer:

```bash
composer require jeffersongoncalves/laravel-faq
```

Publish and run the migrations:

```bash
php artisan vendor:publish --tag="faq-migrations"
php artisan migrate
```

Publish the config file (optional):

```bash
php artisan vendor:publish --tag="faq-config"
```

## Configuration

The config file (`config/faq.php`) covers:

### Table Names

```php
'table_names' => [
    'categories' => 'faq_categories',
    'faqs' => 'faqs',
],
```

### Locales

```php
'locales' => config('app.available_locales')
    ? array_keys(config('app.available_locales'))
    : [config('app.locale', 'en')],
```

Reads from `app.available_locales` (an array keyed by locale code, e.g. `['en' => 'English', 'pt_BR' => 'Português']`) when present, otherwise falls back to the app's default locale. The package also configures `spatie/laravel-translatable`'s fallback behavior on boot, so a missing translation for the current locale falls back to `app.fallback_locale` (or any available locale if that is also missing).

## Usage

### Categories

```php
use JeffersonGoncalves\Faq\Models\FaqCategory;

$category = FaqCategory::create([
    'name' => ['en' => 'Billing', 'pt_BR' => 'Faturamento'],
    'slug' => 'billing',
    'order' => 1,
    'is_active' => true,
]);

$category->getTranslation('name', 'pt_BR'); // 'Faturamento'
$category->setTranslation('name', 'es', 'Facturación');
$category->save();
```

### FAQs

```php
use JeffersonGoncalves\Faq\Models\Faq;

$faq = Faq::create([
    'faq_category_id' => $category->id, // nullable — FAQs can exist without a category
    'question' => ['en' => 'How do I update my payment method?'],
    'answer' => ['en' => 'Go to Settings > Billing and click "Update payment method".'],
    'order' => 1,
    'is_active' => true,
]);

$faq->question; // resolved for the current app locale, with fallback
$faq->category; // the related FaqCategory, or null
```

### Scopes

```php
FaqCategory::active()->ordered()->get();
Faq::active()->ordered()->get();

$category->faqs()->active()->ordered()->get();
```

### Translations

Because both models use `Spatie\Translatable\HasTranslations`, the full [spatie/laravel-translatable API](https://github.com/spatie/laravel-translatable) is available:

```php
$faq->getTranslation('question', 'pt_BR');
$faq->setTranslation('question', 'pt_BR', 'Como atualizo minha forma de pagamento?');
$faq->getTranslations('question'); // ['en' => '...', 'pt_BR' => '...']
$faq->translate('question', 'pt_BR');
```

## Testing

```bash
composer test
```

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](.github/CONTRIBUTING.md) for details.

## Security Vulnerabilities

Please review [our security policy](../../security/policy) on how to report security vulnerabilities.

## Credits

- [Jefferson Gonçalves](https://github.com/jeffersongoncalves)
- [All Contributors](../../contributors)

## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.

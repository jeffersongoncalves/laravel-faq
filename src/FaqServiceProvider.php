<?php

namespace JeffersonGoncalves\Faq;

use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;
use Spatie\Translatable\Translatable;

class FaqServiceProvider extends PackageServiceProvider
{
    public static string $name = 'faq';

    public function configurePackage(Package $package): void
    {
        $package
            ->name(static::$name)
            ->hasConfigFile()
            ->hasMigrations([
                'create_faq_categories_table',
                'create_faqs_table',
            ]);
    }

    public function packageBooted(): void
    {
        app(Translatable::class)->fallback(
            fallbackLocale: config('app.fallback_locale', 'en'),
            fallbackAny: true,
        );
    }
}

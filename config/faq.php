<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Table Names
    |--------------------------------------------------------------------------
    |
    | Table names used by the package. Override these if they collide with
    | existing tables in your application.
    |
    */
    'table_names' => [
        'categories' => 'faq_categories',
        'faqs' => 'faqs',
    ],

    /*
    |--------------------------------------------------------------------------
    | Locales
    |--------------------------------------------------------------------------
    |
    | Locales supported by the translatable fields (category name, question,
    | answer). Mirrors spatie/laravel-translatable's own convention: reads
    | from `app.available_locales` (an array keyed by locale code, e.g.
    | ['en' => 'English', 'pt_BR' => 'Português']) when present, otherwise
    | falls back to the application's default locale.
    |
    */
    'locales' => config('app.available_locales')
        ? array_keys(config('app.available_locales'))
        : [config('app.locale', 'en')],
];

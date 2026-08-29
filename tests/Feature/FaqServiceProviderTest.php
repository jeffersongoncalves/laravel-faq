<?php

use Illuminate\Support\Facades\Schema;

it('registers the config file', function () {
    expect(config('faq.table_names.categories'))->toBe('faq_categories')
        ->and(config('faq.table_names.faqs'))->toBe('faqs');
});

it('registers the migrations and creates the tables', function () {
    expect(Schema::hasTable('faq_categories'))->toBeTrue()
        ->and(Schema::hasTable('faqs'))->toBeTrue();
});

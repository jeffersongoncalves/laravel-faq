<?php

use JeffersonGoncalves\Faq\Models\Faq;
use JeffersonGoncalves\Faq\Models\FaqCategory;

it('stores and retrieves translations per locale', function () {
    $category = FaqCategory::factory()->create([
        'name' => ['en' => 'Billing', 'pt_BR' => 'Faturamento'],
    ]);

    expect($category->getTranslation('name', 'en'))->toBe('Billing')
        ->and($category->getTranslation('name', 'pt_BR'))->toBe('Faturamento');
});

it('falls back to the fallback locale when a translation is missing', function () {
    config(['app.fallback_locale' => 'en']);

    $category = FaqCategory::factory()->create([
        'name' => ['en' => 'Billing'],
    ]);

    expect($category->getTranslation('name', 'pt_BR'))->toBe('Billing');
});

it('has many faqs', function () {
    $category = FaqCategory::factory()->create();
    Faq::factory()->count(3)->create(['faq_category_id' => $category->id]);

    expect($category->faqs)->toHaveCount(3);
});

it('scopes active categories', function () {
    FaqCategory::factory()->create(['is_active' => true]);
    FaqCategory::factory()->inactive()->create();

    expect(FaqCategory::active()->count())->toBe(1);
});

it('scopes categories ordered by the order column', function () {
    $second = FaqCategory::factory()->create(['order' => 2]);
    $first = FaqCategory::factory()->create(['order' => 1]);

    expect(FaqCategory::ordered()->pluck('id')->all())->toBe([$first->id, $second->id]);
});

it('uses slug as the route key', function () {
    $category = FaqCategory::factory()->create();

    expect($category->getRouteKeyName())->toBe('slug');
});

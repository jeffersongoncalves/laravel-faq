<?php

use JeffersonGoncalves\Faq\Models\Faq;
use JeffersonGoncalves\Faq\Models\FaqCategory;

it('stores and retrieves translated question and answer per locale', function () {
    $faq = Faq::factory()->create([
        'question' => ['en' => 'How do I reset my password?', 'pt_BR' => 'Como redefino minha senha?'],
        'answer' => ['en' => 'Click "forgot password".', 'pt_BR' => 'Clique em "esqueci minha senha".'],
    ]);

    expect($faq->getTranslation('question', 'en'))->toBe('How do I reset my password?')
        ->and($faq->getTranslation('question', 'pt_BR'))->toBe('Como redefino minha senha?')
        ->and($faq->getTranslation('answer', 'pt_BR'))->toBe('Clique em "esqueci minha senha".');
});

it('falls back to the fallback locale when a translation is missing', function () {
    config(['app.fallback_locale' => 'en']);

    $faq = Faq::factory()->create([
        'question' => ['en' => 'How do I reset my password?'],
    ]);

    expect($faq->getTranslation('question', 'pt_BR'))->toBe('How do I reset my password?');
});

it('allows faqs without a category', function () {
    $faq = Faq::factory()->create(['faq_category_id' => null]);

    expect($faq->category)->toBeNull();
});

it('belongs to a category', function () {
    $category = FaqCategory::factory()->create();
    $faq = Faq::factory()->create(['faq_category_id' => $category->id]);

    expect($faq->category)->toBeInstanceOf(FaqCategory::class)
        ->and($faq->category->id)->toBe($category->id);
});

it('scopes active faqs', function () {
    Faq::factory()->create(['is_active' => true]);
    Faq::factory()->inactive()->create();

    expect(Faq::active()->count())->toBe(1);
});

it('scopes faqs ordered by the order column', function () {
    $second = Faq::factory()->create(['order' => 2]);
    $first = Faq::factory()->create(['order' => 1]);

    expect(Faq::ordered()->pluck('id')->all())->toBe([$first->id, $second->id]);
});

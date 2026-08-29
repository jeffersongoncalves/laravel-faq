<?php

namespace JeffersonGoncalves\Faq\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\Faq\Database\Factories\FaqFactory;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property int|null $faq_category_id
 * @property array<string, string> $question
 * @property array<string, string> $answer
 * @property int $order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read FaqCategory|null $category
 */
class Faq extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = [
        'question',
        'answer',
    ];

    protected $fillable = [
        'faq_category_id',
        'question',
        'answer',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('faq.table_names.faqs', parent::getTable());
    }

    protected static function newFactory(): FaqFactory
    {
        return FaqFactory::new();
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(FaqCategory::class, 'faq_category_id');
    }

    /** @param Builder<static> $query */
    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    /** @param Builder<static> $query */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('order');
    }
}

<?php

namespace JeffersonGoncalves\Faq\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Carbon;
use JeffersonGoncalves\Faq\Database\Factories\FaqCategoryFactory;
use Spatie\Translatable\HasTranslations;

/**
 * @property int $id
 * @property array<string, string> $name
 * @property string $slug
 * @property int $order
 * @property bool $is_active
 * @property Carbon|null $created_at
 * @property Carbon|null $updated_at
 * @property-read Collection<int, Faq> $faqs
 */
class FaqCategory extends Model
{
    use HasFactory;
    use HasTranslations;

    public array $translatable = [
        'name',
    ];

    protected $fillable = [
        'name',
        'slug',
        'order',
        'is_active',
    ];

    protected $casts = [
        'order' => 'integer',
        'is_active' => 'boolean',
    ];

    public function getTable(): string
    {
        return config('faq.table_names.categories', parent::getTable());
    }

    protected static function newFactory(): FaqCategoryFactory
    {
        return FaqCategoryFactory::new();
    }

    public function faqs(): HasMany
    {
        return $this->hasMany(Faq::class);
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

    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}

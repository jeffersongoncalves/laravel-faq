<?php

namespace JeffersonGoncalves\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;
use JeffersonGoncalves\Faq\Models\FaqCategory;

/** @extends Factory<FaqCategory> */
class FaqCategoryFactory extends Factory
{
    protected $model = FaqCategory::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        $name = fake()->unique()->words(3, true);

        return [
            'name' => ['en' => $name],
            'slug' => Str::slug($name).'-'.fake()->unique()->numberBetween(1, 1000000),
            'order' => 0,
            'is_active' => true,
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'is_active' => false,
        ]);
    }
}

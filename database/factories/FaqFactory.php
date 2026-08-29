<?php

namespace JeffersonGoncalves\Faq\Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use JeffersonGoncalves\Faq\Models\Faq;

/** @extends Factory<Faq> */
class FaqFactory extends Factory
{
    protected $model = Faq::class;

    /** @return array<string, mixed> */
    public function definition(): array
    {
        return [
            'faq_category_id' => null,
            'question' => ['en' => fake()->unique()->sentence().'?'],
            'answer' => ['en' => fake()->paragraph()],
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

<?php

namespace Database\Factories;

use App\Models\Classified;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classified>
 */
class ClassifiedFactory extends Factory
{
    protected $model = Classified::class;

    public function definition(): array
    {
        return [
            'title' => $this->faker->sentence(6),
            'author' => $this->faker->name(),
            'body' => $this->faker->paragraphs(2, true),
            'source_url' => $this->faker->url(),
            'is_sponsored' => false,
            'comments' => 0,
        ];
    }
}

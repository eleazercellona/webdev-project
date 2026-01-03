<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Post>
 */
class PostFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // Generate a random sentence for the title
            'title' => fake()->sentence(),
            
            // Generate 3-5 paragraphs for the body
            'body' => fake()->paragraphs(3, true),
            
            // Randomly assign it to an existing User (IDs 1 to 5)
            // Adjust '5' if you have fewer users, or use User::factory()
            'user_id' => \App\Models\User::inRandomOrder()->first()->id ?? 1,
            
            // 70% chance of being published, 30% chance of being a draft
            'is_published' => fake()->boolean(70),
        ];
    }
}

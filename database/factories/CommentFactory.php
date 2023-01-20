<?php

namespace Database\Factories;

use App\Models\Article;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Comment>
 */
class CommentFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        if(Article::count() < 5) {
            Article::factory()->count(5)->create();
        }

        return [
            'content' => $this->faker->sentence,
            'user_id' => User::inRandomOrder()->first()->id,
            'article_id' => Article::inRandomOrder()->first()->id,
        ];
    }
}

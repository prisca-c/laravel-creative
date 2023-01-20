<?php

namespace Database\Factories;

use App\Models\ArticleTagRelation;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ArticleTagRelation>
 */
class ArticleTagRelationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $tags = Tag::inRandomOrder()->limit(1)->get();
        $article = DB::table('articles')->inRandomOrder()->limit(1)->get();

        // Check if the article has already a tag && if the tag is already assigned to the article
        if (ArticleTagRelation::where('article_id', $article[0]->id)->where('tag_id', $tags[0]->id)->exists()) {
            // Recreate the factory if the article already has a tag
            return $this->definition();
        } else {
            // Create the relation between the article and the tag
            return [
                'article_id' => $article[0]->id,
                'tag_id' => $tags[0]->id,
            ];
        }
    }
}

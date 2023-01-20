<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\Article;
use App\Models\ArticleTagRelation;
use App\Models\Comment;
use App\Models\Tag;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use function Ramsey\Uuid\v4;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run(): void
    {
        User::factory()->count(5)->create();
        Tag::factory()->count(5)->create();
        Article::factory()->count(10)->create();
        ArticleTagRelation::factory()->count(10)->create();
        Comment::factory()->count(5)->create();
    }
}

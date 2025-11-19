<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\Comment;
use App\Models\User;
use Illuminate\Database\Seeder;

class SampleContentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = \Faker\Factory::create();

        // select users we seeded (admin, user, user1..user8)
        $users = User::where(function ($q) {
            $q->where('email', 'admin@admin.com')
                ->orWhere('email', 'user@user.com')
                ->orWhere('email', 'user1@example.com')
                ->orWhere('email', 'user2@example.com')
                ->orWhere('email', 'user3@example.com')
                ->orWhere('email', 'user4@example.com')
                ->orWhere('email', 'user5@example.com')
                ->orWhere('email', 'user6@example.com')
                ->orWhere('email', 'user7@example.com')
                ->orWhere('email', 'user8@example.com');
        })->get();

        if ($users->isEmpty()) {
            return;
        }

        // For each user, create 1-2 posts
        foreach ($users as $user) {
            $postsCount = rand(1, 2);
            for ($i = 0; $i < $postsCount; $i++) {
                $post = Post::factory()->create([
                    'user_id' => $user->id,
                    'author' => $user->name,
                    'content' => $faker->paragraphs(rand(2, 4), true),
                    'title' => $faker->sentence(6),
                ]);

                // create 1-3 comments from random users
                $commentsCount = rand(1, 3);
                for ($c = 0; $c < $commentsCount; $c++) {
                    $commentUser = $users->random();
                    Comment::create([
                        'user_id' => $commentUser->id,
                        'post_id' => $post->id,
                        'body' => $faker->sentences(rand(1, 3), true),
                    ]);
                }
            }
        }
    }
}

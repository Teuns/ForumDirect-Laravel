<?php
use Faker\Generator as Faker;
$factory->define(App\Post::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'thread_id' => 1,
        'subforum_id' => $faker->unique()->randomDigit,
        'body' => $faker->paragraph
    ];
});
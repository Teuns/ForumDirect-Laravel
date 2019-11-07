<?php

use Faker\Generator as Faker;

$factory->define(App\Thread::class, function (Faker $faker) {
    return [
        'user_id' => 1,
        'lastpost_uid' => 1,
        'subforum_id' => 1,
        'title' => $faker->unique()->sentence,
        'slug' => $faker->unique()->sentence,
        'subforum_id' => $faker->unique()->randomDigit,
        'body' => $faker->paragraph
    ];
});
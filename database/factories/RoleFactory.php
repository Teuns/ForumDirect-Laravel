<?php

use App\Role;
use Illuminate\Support\Str;
use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(Role::class, function (Faker $faker) {
    return [
        'name' => $faker->realText($maxNbChars = 10),
        'slug' => $faker->realText($maxNbChars = 10),
        'permissions' => json_decode('{"create-thread": true, "update-thread": true, "create-post": true, "update-post": true}'),
    ];
});
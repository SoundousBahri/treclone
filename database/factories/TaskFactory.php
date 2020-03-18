<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\Category;
use App\Task;
use App\User;
use Faker\Generator as Faker;
use Illuminate\Support\Str;

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

$factory->define(Task::class, function (Faker $faker) {
    return [
        'name' => $faker->sentence(12, true),
        'order' => $faker->randomNumber(4, false),
        'category_id'=> Category::inRandomOrder()->first(),
        'user_id'=> User::inRandomOrder()->first(),
    ];
});

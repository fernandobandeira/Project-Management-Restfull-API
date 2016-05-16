<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

$factory->define(CodeProject\Entities\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->safeEmail,
        'password' => bcrypt(str_random(10)),
        'remember_token' => str_random(10),
    ];
});

$factory->define(CodeProject\Entities\Client::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'responsible' => $faker->name,
        'email' => $faker->safeEmail,
        'phone' => $faker->phoneNumber,
        'address' => $faker->address,
        'obs' => $faker->sentence,
    ];
});

$factory->define(CodeProject\Entities\Project::class, function (Faker\Generator $faker) {
    return [
        'owner_id' => $faker->numberBetween(1,6),
        'client_id' => $faker->numberBetween(1,7),
        'name' => $faker->word,
        'description' => $faker->sentence,
        'progress' => $faker->numberBetween(1,100),
        'status' => $faker->numberBetween(1,3),
        'due_date' => $faker->date,
    ];
});

$factory->define(CodeProject\Entities\ProjectNote::class, function (Faker\Generator $faker) {
    return [
        'project_id' => $faker->numberBetween(1,5),
        'title' => $faker->word,
        'note' => $faker->paragraph,
    ];
});

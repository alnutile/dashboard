<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EpicSprintRecord;
use Faker\Generator as Faker;

$factory->define(EpicSprintRecord::class, function (Faker $faker) {
    return [
        "name" => $faker->sentence(),
        "jira_key" => $faker->word,
        "jira_id" => $faker->randomNumber(),
        "jira_type" => "epic",
        "done" => false, 
        'active' => true,
        'summary' => $faker->sentence()
    ];
});

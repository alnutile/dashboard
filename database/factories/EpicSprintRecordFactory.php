<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EpicSprintRecord;
use Carbon\Carbon;
use Faker\Generator as Faker;

$factory->define(EpicSprintRecord::class, function (Faker $faker) {
    return [
        "name" => $faker->sentence(),
        "jira_key" => $faker->word,
        "jira_id" => $faker->randomNumber(),
        "jira_type" => "epic",
        "done" => false,
        "due_date" => Carbon::now(),
        "number_of_devs" => 1,
        'active' => true,
        'summary' => $faker->sentence()
    ];
});

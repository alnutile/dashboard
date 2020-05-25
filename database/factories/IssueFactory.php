<?php

/** @var \Illuminate\Database\Eloquent\Factory $factory */

use App\EpicSprintRecord;
use App\Issue;
use Faker\Generator as Faker;

$factory->define(Issue::class, function (Faker $faker) {
    return [
        "name" => $faker->sentences(2, true),
        "jira_description" => $faker->sentences(2, true),
        "epic_sprint_record_id" => function () {
            return factory(EpicSprintRecord::class)->create()->id;
        },
        "seconds" => $faker->randomNumber(),
        "jira_key" => sprintf("FOO-%s",  $faker->randomNumber()),
        "jira_id" => $faker->randomNumber(),
    ];
});

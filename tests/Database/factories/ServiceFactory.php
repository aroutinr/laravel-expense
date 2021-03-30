<?php

use AroutinR\Expense\Tests\Models\Service;
use Faker\Generator as Faker;

$factory->define(Service::class, function (Faker $faker) {
	return [
		'title' => $faker->sentence()
	];
});
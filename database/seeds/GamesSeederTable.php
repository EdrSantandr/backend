<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GamesSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Game');
        //Para juego TITLE
        DB::table('games')->insert([
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(3, true),
            'time_min' => 30000, //30 segundos
            'time_max' => 60000, //60 segundos
            'characters_min' => 1,
            'characters_max' => 200,
        ]);
        //Para juego TITLE
        DB::table('games')->insert([
            'title' => $faker->sentence(),
            'description' => $faker->paragraph(3, true),
            'time_min' => 60000, //60 segundos (1 MIN)
            'time_max' => 180000, //180 segundos (3 MIN)
            'characters_min' => 10,
            'characters_max' => 1000,
        ]);
    }
}

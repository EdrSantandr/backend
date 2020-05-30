<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class GroupsSeederTable extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Group');
        for( $i=1 ; $i <= 15 ; $i++ ){
            DB::table('groups')->insert([
                'title' => $faker->sentence(),
                'description' => $faker->sentence(),
                'photo' => 'avatar.png',
                'url' => 'https://www.facebook.com/EdrSantandr'
            ]);
        }
    }
}

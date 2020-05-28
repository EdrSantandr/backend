<?php

use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class StoriesSeederTable extends Seeder
{
    /* STRUCTURE TABLE STORIES
    
    $table->string('title');
    $table->string('content');
    $table->timestamp('date_ini');
    $table->timestamp('date_end');
    $table->bigInteger('votes');
    $table->bigInteger('turns');
    $table->bigInteger('last_user_id');
    $table->string('state');

     Fin Campos para tabla stories*/
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker::create('App\Story');
        for( $i=1 ; $i <= 15 ; $i++ ){
            DB::table('stories')->insert([
                'title' => $faker->sentence(),
                'content' => $faker->paragraph(3, true),
                'date_ini' => \Carbon\Carbon::now(),
                'date_end' => \Carbon\Carbon::now(),
                'votes' => $faker->randomNumber(),
                'turns' => $faker->randomNumber(),
                'last_user_id' => 1,
                'state' => 'ini',
                'created_at' => \Carbon\Carbon::now(),
                'Updated_at' => \Carbon\Carbon::now(),
            ]);
        }
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stories', function (Blueprint $table) {
            $table->id();
            /* Campos para tabla stories*/
            $table->string('title');
            $table->string('content');
            $table->timestamp('date_ini');
            $table->timestamp('date_end');
            $table->bigInteger('votes');
            $table->bigInteger('turns');
            $table->bigInteger('last_user_id');
            $table->string('state');
            /* Fin Campos para tabla stories*/
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stories');
    }
}

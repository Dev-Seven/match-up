<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGamesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('games', function (Blueprint $table) {
            $table->id();
            $table->string('title')->nullable();
            $table->string('description')->nullable();
            $table->string('instruction')->nullable();
            $table->string('title_de')->nullable();
            $table->string('description_de')->nullable();
            $table->string('instruction_de')->nullable();
            $table->string('type')->nullable();
            $table->string('game_round')->nullable();
            $table->string('game_value')->nullable();
            $table->string('timer')->nullable();
            $table->string('min_player_require')->nullable();
            $table->string('logo')->nullable();
            $table->string('banner')->nullable();
            $table->string('video')->nullable();
            $table->string('logo_de')->nullable();
            $table->string('banner_de')->nullable();
            $table->string('video_de')->nullable();




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
        Schema::dropIfExists('games');
    }
}

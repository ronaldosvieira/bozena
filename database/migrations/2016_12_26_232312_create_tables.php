<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTables extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('player', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('tournament', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('player_tournament', function (Blueprint $table) {
            $table->integer('player_id');
            $table->integer('tournament_id');
            $table->string('team');

            $table->foreign('player_id')->references('id')->on('player');
            $table->foreign('tournament_id')->references('id')->on('tournament');
        });

        Schema::create('match', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('tournament_id');

            $table->integer('home_player_id');
            $table->integer('away_player_id');

            $table->foreign('tournament_id')->references('id')->on('tournament');
            $table->foreign('home_player_id')->references('id')->on('player');
            $table->foreign('away_player_id')->references('id')->on('player');
        });

        Schema::create('goal', function (Blueprint $table) {
            $table->integer('match_id');
            $table->string('team');
            $table->string('scorer');
            $table->string('assister')->nullable();

            $table->foreign('match_id')->references('id')->on('match');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::dropIfExists('player');
        Schema::dropIfExists('tournament');
        Schema::dropIfExists('player_tournament');
        Schema::dropIfExists('match');
        Schema::dropIfExists('goal');
    }
}

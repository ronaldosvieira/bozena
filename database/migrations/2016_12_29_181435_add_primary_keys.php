<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPrimaryKeys extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('player_tournament', function ($table) {
            $table->primary(['player_id', 'tournament_id']);
        });

        Schema::table('goal', function ($table) {
            $table->increments('id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('goal', function ($table) {
            $table->dropColumn('id');
        });

        Schema::table('player_tournament', function ($table) {
            $table->dropPrimary('player_tournament_id_primary');
        });
    }
}

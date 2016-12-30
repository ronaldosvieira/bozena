<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMatchStatus extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('match_state', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('can_add_goals');
            $table->boolean('is_done');
        });

        DB::table('match_state')->insert([
            'id' => 1, 'name' => 'Não iniciado',
            'can_add_goals' => false, 'is_done' => false
        ]);

        DB::table('match_state')->insert([
            'id' => 2, 'name' => 'Em jogo',
            'can_add_goals' => true, 'is_done' => false
        ]);

        DB::table('match_state')->insert([
            'id' => 3, 'name' => 'Terminado',
            'can_add_goals' => false, 'is_done' => true
        ]);

        Schema::table('match', function ($table) {
            $table->integer('match_state_id')->default(1);

            $table->foreign('match_state_id')
                ->references('id')
                ->on('match_state');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('match', function ($table) {
            $table->dropColumn('match_state_id');
        });

        Schema::dropIfExists('match_state');

    }
}

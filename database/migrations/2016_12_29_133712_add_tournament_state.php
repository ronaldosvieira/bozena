<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTournamentState extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('tournament_state', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->boolean('can_edit');
            $table->boolean('can_remove');
        });

        DB::table('tournament_state')->insert([
            'id' => 1, 'name' => 'Inativo', 'can_edit' => true, 'can_remove' => true
        ]);

        DB::table('tournament_state')->insert([
            'id' => 2, 'name' => 'Ativo', 'can_edit' => false, 'can_remove' => false
        ]);

        DB::table('tournament_state')->insert([
            'id' => 3, 'name' => 'Terminado', 'can_edit' => false, 'can_remove' => true
        ]);

        Schema::table('tournament', function ($table) {
            $table->integer('tournament_state_id')->default(1);

            $table->foreign('tournament_state_id')
                ->references('id')
                ->on('tournament_state');
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('tournament', function ($table) {
            $table->dropColumn('tournament_state_id');
        });

        Schema::dropIfExists('tournament_state');

    }
}

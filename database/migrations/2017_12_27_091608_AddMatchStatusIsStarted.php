<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMatchStatusIsStarted extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('match_state', function (Blueprint $table) {
            $table->boolean('is_started')->default(true);
        });

        DB::table('match_state')
            ->where('id', 1)
            ->update(['is_started' => false]);

        DB::table('match_state')
            ->where('id', 2)
            ->update(['is_started' => true]);

        DB::table('match_state')
            ->where('id', 3)
            ->update(['is_started' => true]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('match_state', function (Blueprint $table) {
            $table->dropColumn('is_started');
        });
    }
}

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('tournament_scores', function (Blueprint $table) {
            $table->id();
            $table->integer('team_id');
            $table->integer('plays');
            $table->integer('wins');
            $table->integer('draws');
            $table->integer('losses');
            $table->integer('goal_differences');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tournament_scores');
    }
};

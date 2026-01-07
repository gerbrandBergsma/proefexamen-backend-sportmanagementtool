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
        Schema::create('training_attendances', function (Blueprint $table) {
            $table->id();
            $table->foreignId('training_id')
                  ->constrained('trainings')
                  ->onDelete('cascade');
            $table->foreignId('player_id')
                  ->constrained('players')
                  ->onDelete('cascade');
            $table->enum('status', ['present', 'absent', 'late', 'unknown'])
                  ->default('unknown');
            $table->timestamps();

            $table->unique(['training_id', 'player_id']); // voorkomt dubbele registratie
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('training_attendances');
    }
};

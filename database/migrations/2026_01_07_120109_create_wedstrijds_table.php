<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('wedstrijden', function (Blueprint $table) {
            $table->id();
            $table->foreignId('team_thuis_id')->constrained('teams')->onDelete('cascade');
            $table->foreignId('team_uit_id')->constrained('teams')->onDelete('cascade');
            $table->date('datum');
            $table->string('locatie');
            $table->integer('uitslag_thuis')->nullable();
            $table->integer('uitslag_uit')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('wedstrijden');
    }
};

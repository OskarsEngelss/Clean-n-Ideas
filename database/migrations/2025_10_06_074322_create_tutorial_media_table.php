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
        Schema::create('tutorial_media', function (Blueprint $table) {
            $table->id(); // Primārā atslēga (PK)
            $table->foreignId('tutorial_id')->constrained('experiences')->onDelete('cascade'); // Ārējā atslēga (FK) ar pamācību (experience) (un dzēšana)
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // Ārējā atslēga (FK) ar lietotāju (un dzēšana)
            $table->string('type'); // Teksta kolonna medija tipam ('image', 'video')
            $table->string('path'); // Faila atrašanās vieta iekš servera
            $table->timestamps(); // Izveides/atjaunošanas laiki
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tutorial_media');
    }
};

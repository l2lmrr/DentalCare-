<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('plages_horaires', function (Blueprint $table) {
            $table->id();
            $table->foreignId('praticien_id')->constrained()->onDelete('cascade');
            $table->enum('jour', ['lundi', 'mardi', 'mercredi', 'jeudi', 'vendredi', 'samedi']);
            $table->time('heure_debut');
            $table->time('heure_fin');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('plages_horaires');
    }
};
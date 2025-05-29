<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('dossiers_medicaux', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('praticien_id')->constrained('users')->onDelete('cascade');
            $table->text('diagnostic');
            $table->text('traitement');
            $table->text('prescription');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dossiers_medicaux');
    }
};
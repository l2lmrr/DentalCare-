<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('dentists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('bio')->nullable();
            $table->string('license_number');
            $table->string('photo')->nullable();
            $table->integer('years_of_experience')->default(0);
            $table->json('working_hours')->nullable();
            $table->softDeletes();
            $table->timestamps();
            
            $table->unique('user_id'); // One-to-one relationship
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('dentists');
    }
};
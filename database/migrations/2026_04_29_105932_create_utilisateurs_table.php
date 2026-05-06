<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('utilisateurs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('equipe_id')->constrained('equipes')->onDelete('cascade');
            $table->string('nom');
            $table->string('email')->unique();
            $table->string('tel')->nullable();
            $table->string('motdepasse');
            $table->string('avatar')->nullable();
            $table->enum('statut', ['actif', 'inactif'])->default('actif');
            $table->rememberToken();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('utilisateurs');
    }
};
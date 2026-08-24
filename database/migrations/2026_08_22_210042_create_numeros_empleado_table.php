<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('numeros_empleado', function (Blueprint $table) {

            $table->id();

            // Login del usuario
            $table->string('login', 255);

            // Número de empleado ÚNICO
            $table->string('numero_empleado', 50)->unique();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('numeros_empleado');
    }
};
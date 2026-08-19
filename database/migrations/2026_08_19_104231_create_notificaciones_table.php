<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('notificaciones', function (Blueprint $table) {

            $table->id();

            // Usuario que recibe la notificación
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Categoría de la notificación
            $table->string('tipo');

            // Título que verá el usuario
            $table->string('titulo');

            // Mensaje de la notificación
            $table->text('mensaje')->nullable();

            // Icono que mostrará la notificación
            $table->string('icono')->nullable();

            // Color del icono o categoría
            $table->string('color')->nullable();

            // URL a donde llevará al hacer click
            $table->text('url')->nullable();

            // Para saber si ya la leyó
            $table->boolean('leida')->default(false);

            // Opcional: ID del registro relacionado
            $table->unsignedBigInteger('referencia_id')->nullable();

            $table->timestamps();

        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notificaciones');
    }
};
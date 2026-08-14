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
        Schema::create('avisos', function (Blueprint $table) {
    $table->id();

    $table->string('titulo');

    $table->enum('tipo', [
        'mantenimiento',
        'incidente',
        'informativo',
        'general',
    ]);

    $table->enum('importancia', [
        'critica',
        'alta',
        'media',
        'normal',
    ]);

    $table->dateTime('fecha_inicio');

    $table->dateTime('fecha_fin')->nullable();

    $table->enum('aplica_a', [
        'todos',
        'departamento',
        'usuarios',
    ])->default('todos');

    $table->text('descripcion');

    $table->string('afecta_a')->nullable();

    $table->boolean('mostrar_notificaciones')->default(true);

    $table->boolean('fijado')->default(false);

    $table->string('archivo')->nullable();

    $table->foreignId('publicado_por')
        ->constrained('users')
        ->cascadeOnDelete();

    $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('avisos');
    }
};

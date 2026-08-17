<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('solicitudes_cambio', function (Blueprint $table) {

            $table->id();

            // Usuario que solicita el cambio
            $table->foreignId('user_id')
                ->constrained('users')
                ->cascadeOnDelete();

            // Campo que desea modificar
            $table->string('campo', 50);

            // Información actual
            $table->text('valor_actual')->nullable();

            // Información nueva propuesta
            $table->text('nuevo_valor');

            // Motivo del cambio
            $table->text('motivo');

            // Estado de la solicitud
            $table->enum('estado', [
                'pendiente',
                'aprobada',
                'rechazada'
            ])->default('pendiente');

            // Comentario del administrador
            $table->text('comentario_admin')->nullable();

            // Administrador que revisó
            $table->foreignId('revisado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            // Fecha de revisión
            $table->timestamp('revisado_at')->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('solicitudes_cambio');
    }
};
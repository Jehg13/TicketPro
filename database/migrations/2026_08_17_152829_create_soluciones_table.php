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
        Schema::create('soluciones', function (Blueprint $table) {

            $table->id();

            // Ticket al que pertenece la solución
            $table->foreignId('ticket_id')
                ->constrained('ticket_u_s')
                ->cascadeOnDelete();

            // Usuario de tecnologías que solucionó el ticket
            $table->foreignId('solucionado_por')
                ->constrained('users')
                ->restrictOnDelete();

            // Descripción del problema solucionado
            $table->text('problema_solucionado');

            // Descripción de la solución aplicada
            $table->text('solucion');

            // Ruta de la firma digital
            $table->string('firma')->nullable();

            // Evidencias de la solución
            $table->json('evidencia')->nullable();

            // Nombre del usuario que firmó
            $table->string('nombre_firmante')->nullable();

            // Fecha y hora en que se solucionó
            $table->dateTime('fecha_solucion')->nullable();

            // Fecha y hora en que el usuario firmó
            $table->dateTime('fecha_firma')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('soluciones');
    }
};
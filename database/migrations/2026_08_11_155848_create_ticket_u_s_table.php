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
        Schema::create('ticket_u_s', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained();
            $table->string('titulo');
            $table->string('tipo_falla');
            $table->enum('prioridad',['Critica','Alta','Media','Normal']);
            $table->text('descripcion');
            $table->boolean('afecta_otros')->default(false);
            $table->boolean('es_recurrente')->default(false);
            $table->text('comentarios')->nullable();
            $table->json('evidencia')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_u_s');
    }
};

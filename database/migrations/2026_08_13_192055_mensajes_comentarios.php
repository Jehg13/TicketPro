<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('ticket_comentarios', function (Blueprint $table) {

            $table->id();
            $table->foreignId('ticket_id')
                ->constrained('ticket_u_s')
                ->cascadeOnDelete();
            $table->foreignId('usuario_id')
                ->constrained('users')
                ->cascadeOnDelete();
            $table->text('mensaje')->nullable();
            $table->string('archivo', 500)->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_comentarios');
    }
};
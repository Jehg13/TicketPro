<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_u_s', function (Blueprint $table) {
            $table->foreignId('tomado_por')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();
        });
    }

    public function down(): void
    {
        Schema::table('ticket_u_s', function (Blueprint $table) {
            $table->dropForeign(['tomado_por']);
            $table->dropColumn('tomado_por');
        });
    }
};
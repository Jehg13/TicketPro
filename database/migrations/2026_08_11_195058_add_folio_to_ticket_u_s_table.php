<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('ticket_u_s', function (Blueprint $table) {
            $table->string('folio')->unique()->after('id');
        });
    }

    public function down(): void
    {
        Schema::table('ticket_u_s', function (Blueprint $table) {
            $table->dropUnique(['folio']);
            $table->dropColumn('folio');
        });
    }
};
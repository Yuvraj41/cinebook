<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->foreignId('show_id')
                ->nullable()
                ->after('booking_id')
                ->constrained('shows')
                ->cascadeOnDelete();

            $table->unique(['show_id', 'seat_number']);
        });
    }

    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropUnique(['show_id', 'seat_number']);
            $table->dropConstrainedForeignId('show_id');
        });
    }
};
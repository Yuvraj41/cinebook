<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->unique(
                ['show_id', 'seat_number'],
                'booking_seats_show_seat_unique'
            );

            $table->unsignedBigInteger('show_id')->nullable(false)->change();
        });
    }

    public function down(): void
    {
        Schema::table('booking_seats', function (Blueprint $table) {
            $table->dropUnique('booking_seats_show_seat_unique');

            $table->unsignedBigInteger('show_id')->nullable()->change();
        });
    }
};

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
        Schema::create('kuisioner_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_booking')->constrained('bookings')->onDelete('cascade');
            $table->foreignId('id_users')->constrained('users')->onDelete('cascade');
            $table->foreignId('paket_id')->constrained('paket_bundlings')->onDelete('cascade');
            $table->decimal('nilai_fasilitas', 5, 2);
            $table->decimal('nilai_harga', 5, 2);
            $table->decimal('nilai_kualitas', 5, 2);
            $table->text('komentar')->nullable();
            $table->timestamps();

            $table->unique(['id_booking', 'paket_id'], 'booking_paket_kuisioner_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('kuisioner_penilaians');
    }
};

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
        Schema::create('paket_penilaians', function (Blueprint $table) {
            $table->id();
            $table->foreignId('paket_id')->constrained('paket_bundlings')->onDelete('cascade');
            $table->decimal('nilai_harga', 5, 2)->nullable();
            $table->decimal('nilai_kualitas', 5, 2)->nullable();
            $table->decimal('nilai_fasilitas', 5, 2)->nullable();
            $table->decimal('skor_akhir', 8, 4)->nullable();
            $table->integer('ranking')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paket_penilaians');
    }
};

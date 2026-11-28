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
        Schema::create('ahp_respondens', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 32)->index();
            $table->string('nama_responden')->nullable();
            $table->decimal('nilai_12', 10, 4);
            $table->decimal('nilai_13', 10, 4);
            $table->decimal('nilai_23', 10, 4);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_respondens');
    }
};

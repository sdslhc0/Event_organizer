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
        Schema::create('ahp_hasil_bobots', function (Blueprint $table) {
            $table->id();
            $table->string('kategori', 32)->unique();
            $table->unsignedInteger('jumlah_responden')->default(0);
            $table->decimal('gm_12', 10, 4);
            $table->decimal('gm_13', 10, 4);
            $table->decimal('gm_23', 10, 4);
            $table->decimal('bobot_1', 8, 4);
            $table->decimal('bobot_2', 8, 4);
            $table->decimal('bobot_3', 8, 4);
            $table->decimal('ideal_1', 8, 4);
            $table->decimal('ideal_2', 8, 4);
            $table->decimal('ideal_3', 8, 4);
            $table->decimal('lambda_max', 8, 4);
            $table->decimal('ci', 8, 4);
            $table->decimal('cr', 8, 4);
            $table->boolean('is_consistent')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ahp_hasil_bobots');
    }
};

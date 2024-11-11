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
        Schema::create('fused_measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->timestamp('time');
            $table->decimal('position_x', 10, 7);
            $table->decimal('position_y', 11, 7);
            $table->decimal('velocity_x', 15, 7);
            $table->decimal('velocity_y', 15, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('fused_measurements');
    }
};

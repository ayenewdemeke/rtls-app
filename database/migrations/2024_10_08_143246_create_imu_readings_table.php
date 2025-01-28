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
        Schema::create('imu_readings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('device_id')->constrained();
            $table->timestamp('time');
            $table->decimal('imu_acceleration_x', 15, 7);
            $table->decimal('imu_acceleration_y', 15, 7);
            $table->decimal('imu_acceleration_z', 15, 7);
            $table->decimal('imu_angular_velocity_x', 15, 7);
            $table->decimal('imu_angular_velocity_y', 15, 7);
            $table->decimal('imu_angular_velocity_z', 15, 7);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('imu_readings');
    }
};

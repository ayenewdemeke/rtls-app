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
        Schema::create('work_zones', function (Blueprint $table) {
            $table->id();
            $table->string('work_zone_id')->unique();
            $table->string('name');
            $table->string('location');
            $table->decimal('latitude', 10, 7);
            $table->decimal('longitude', 11, 7);
            $table->date('start_date');
            $table->foreignId('work_zone_status_id')->constrained();
            $table->foreignId('user_id')->constrained();
            $table->string('image')->nullable();
            $table->string('thumbnail')->nullable();
            $table->text('description')->nullable();
            $table->string('system_id')->unique()->nullable();
            $table->date('system_start_date')->nullable();
            $table->foreignId('system_status_id')->constrained()->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('work_zones');
    }
};

<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('sensor_data', function (Blueprint $table) {
            $table->id();
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->float('tegangan')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('sensor_data'); }
};

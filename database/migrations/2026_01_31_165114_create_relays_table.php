<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('relays', function (Blueprint $table) {
            $table->id();
            $table->boolean('status')->default(0); // 0=OFF, 1=ON
            $table->timestamps();
        });

        // Insert default relay record
        DB::table('relays')->insert([
            'status' => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::dropIfExists('relays');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
{
    Schema::table('devices', function (Blueprint $table) {
        if (!Schema::hasColumn('devices','temperature')) {
            $table->float('temperature')->nullable();
        }
        if (!Schema::hasColumn('devices','humidity')) {
            $table->float('humidity')->nullable();
        }
        if (!Schema::hasColumn('devices','relay_status')) {
            $table->boolean('relay_status')->default(false);
        }
    });
}

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('devices', function (Blueprint $table) {
            //
        });
    }
};

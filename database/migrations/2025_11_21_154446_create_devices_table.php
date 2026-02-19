<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDevicesTable extends Migration
{
    public function up()
    {
        Schema::create('devices', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->string('device_token')->unique();
            $table->boolean('relay_status')->default(false);
            $table->float('temperature')->nullable();
            $table->float('humidity')->nullable();
            $table->float('voltage')->nullable(); // nanti untuk PZEM
            $table->timestamp('last_seen')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('devices');
    }
}

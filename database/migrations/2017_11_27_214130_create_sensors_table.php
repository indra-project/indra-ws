<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSensorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sensors', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->enum('type', [
                'WATER_FLUX',
                'AIR_HUMIDITY',
                'SOIL_MOISTURE',
                'VIBRATION',
                'FIRE_FLAME',
                'TEMPERATURE',
                'PRESENCE_SENSOR'
            ]);
            $table->integer('station_id');
            $table->boolean('active');
            $table->integer('intervals');
            $table->string('unit');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sensors');
    }
}

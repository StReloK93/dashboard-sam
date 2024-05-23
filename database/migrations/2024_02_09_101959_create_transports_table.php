<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transports', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('transport_id');
            $table->double('y', 10, 7);
            $table->double('x', 10, 7);
            $table->integer('speed');
            $table->dateTime('time_message');
            $table->string('geozone')->nullable();
            $table->integer('distance_ex')->nullable();
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
        Schema::dropIfExists('transports');
    }
};

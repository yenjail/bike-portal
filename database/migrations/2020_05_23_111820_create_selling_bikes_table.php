<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellingBikesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('selling_bikes', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('bike_id');
            $table->foreign('bike_id')->references('id')->on('bikes')->onDelete('cascade');

            $table->string('make_year');
            $table->float('kms_run');
            $table->integer('engine_cc');
            $table->string('color');
            $table->float('asking_price');
            $table->unsignedBigInteger('seller_id')->unsigned()->nullable();;
            $table->foreign('seller_id')->references('id')->on('sellers')->onDelete('cascade');

            $table->string('seller_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('additional_details');
            $table->string('post_date');

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
        Schema::dropIfExists('selling_bikes');
    }
}

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
        Schema::create('employees', function (Blueprint $table) {
            $table->id();
            $table->integer('brand_id')->default(0);
            $table->integer('position_id');
            $table->string('name');
            $table->string('sex');
            $table->string('email');
            $table->date('dob');
            $table->string('address');
            $table->string('phone');
            $table->integer('shift_id');
            $table->string('note');
            $table->date('hired_date');
            $table->integer('salary');
            $table->integer('stock_location_id');
            $table->tinyInteger('is_seller');
            $table->string('card');
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by');
            $table->integer('modified_by');
            $table->dateTime('modified_date');
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
        Schema::dropIfExists('employees');
    }
};

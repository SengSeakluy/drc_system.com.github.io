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
        Schema::create('item_types', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('category_id');
            $table->text('description')->nullable();
            $table->tinyInteger('is_ingredient')->default(0);
            $table->tinyInteger('is_car_wash')->default(0);
            $table->integer('created_by');
            $table->integer('modified_by');
            $table->dateTime('modified_date');
            $table->tinyInteger('is_point')->nullable();
            $table->string('photo');
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
        Schema::dropIfExists('item_types');
    }
};

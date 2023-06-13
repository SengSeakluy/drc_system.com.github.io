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
        Schema::create('stock_wastes', function (Blueprint $table) {
            $table->id();
            $table->integer('item_id')->nullable();
            $table->integer('qty')->nullable();
            $table->integer('measure_id');
            $table->text('note')->nullable();
            $table->integer('branch_id');
            $table->integer('stock_location_id');
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
        Schema::dropIfExists('stock_wastes');
    }
};

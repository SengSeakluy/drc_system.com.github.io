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
        Schema::create('stock_counts', function (Blueprint $table) {
            $table->id();
            $table->integer('stock_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->integer('stock_location_id')->nullable();
            $table->integer('item_detail_id')->nullable();
            $table->integer('ending_qty')->nullable();
            $table->integer('measure_id')->nullable();
            $table->integer('stock_balance')->nullable();
            $table->date('count_date')->nullable();
            $table->text('description')->nullable();
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
        Schema::dropIfExists('stock_counts');
    }
};

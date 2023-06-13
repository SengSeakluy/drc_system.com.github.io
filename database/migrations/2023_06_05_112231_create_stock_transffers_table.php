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
        Schema::create('stock_transffers', function (Blueprint $table) {
            $table->id();
            $table->integer('location_from')->default(0);
            $table->integer('location_to')->default(0);
            $table->integer('branch_id_from')->default(0);
            $table->integer('branch_id_to')->default(0);
            $table->integer('item_detail_id')->default(0);
            $table->integer('qty')->default(0);
            $table->integer('measure_id')->default(0);
            $table->integer('measure_qty')->default(0);
            $table->text('note')->nullable();
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
        Schema::dropIfExists('stock_transffers');
    }
};

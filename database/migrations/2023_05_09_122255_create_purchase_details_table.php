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
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->integer('purchase_no')->default(0);
            $table->integer('item_detail_id');
            $table->integer('measure_id');
            $table->integer('measure_qty');
            $table->decimal('unit_cost', 10,2)->default(0.00);
            $table->integer('qty')->default(0);
            $table->decimal('total_amount', 10,2)->default(0.00);
            $table->text('note')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('stock_location_id');
            $table->dateTime('item_alert_date')->nullable();
            $table->dateTime('expired_date')->nullable();
            $table->integer('convert_qty')->nullable();
            $table->dateTime('date')->nullable();
            $table->integer('created_by');
            $table->integer('modified_by')->nullable();
            $table->dateTime('modified_date')->nullable();
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
        Schema::dropIfExists('purchase_details');
    }
};

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
        Schema::create('item_details', function (Blueprint $table) {
            $table->id();
            $table->integer('item_type_id');
            $table->text('name');
            $table->string('part_number');
            $table->string('description')->nullable();
            $table->decimal('whole_price', 10,2);
            $table->decimal('retail_price', 10,2);
            $table->string('photo');
            $table->tinyInteger('cut_stock');
            $table->integer('remain_alert');
            $table->integer('printer_location_id')->default(0);
            $table->integer('like_count')->default(0);
            $table->integer('measure_id')->default(0);
            $table->string('color');
            $table->tinyInteger('status')->default(1);
            $table->tinyInteger('is_package')->default(0);
            $table->decimal('cost', 10,2)->default(0.00);
            $table->integer('customer_type_id')->nullable();
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
        Schema::dropIfExists('item_details');
    }
};

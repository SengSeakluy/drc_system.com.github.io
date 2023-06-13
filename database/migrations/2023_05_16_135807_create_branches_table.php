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
        Schema::create('branches', function (Blueprint $table) {
            $table->id();
            $table->string('name')->nullable();
            $table->text('location')->nullable();
            $table->string('email')->nullable();
            $table->string('phone')->nullable();
            $table->string('description')->nullable();
            $table->string('wifi_password')->nullable();
            $table->string('sale_offline_cash_id')->default(0);
            $table->date('sale_offline_date')->nullable();
            $table->integer('cashier_id');
            $table->string('prefix_retail_sale')->nullable();
            $table->string('prefix_whole_sale')->nullable();
            $table->string('prefix_credit_pay')->nullable();
            $table->tinyInteger('status')->default(1);
            $table->integer('created_by');
            $table->integer('modified_by');
            $table->dateTime('modified_date');
            $table->softDeletes();
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
        Schema::dropIfExists('branches');
    }
};

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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
            $table->integer('type_id');
            $table->string('name');
            $table->string('gender');
            $table->string('email')->nullable();
            $table->string('picture')->nullable();
            $table->date('dob');
            $table->string('address');
            $table->string('phone');
            $table->integer('created_by');
            $table->integer('modified_by');
            $table->dateTime('modified_date');
            $table->integer('is_discount')->default(0);
            $table->tinyInteger('status')->default(1);
            $table->integer('enable')->default(1);
            $table->integer('branch_id');
            $table->string('card_number')->nullable();
            $table->string('card_serial')->nullable();
            $table->string('chip')->nullable();
            $table->decimal('balance', 10,2)->default(0.00);
            $table->integer('point')->nullable();
            $table->date('card_expired')->nullable();
            $table->integer('discount')->nullable();
            $table->date('card_expired_alert')->nullable();
            $table->decimal('amount_remain_alert', 10,2)->nullable();
            $table->dateTime('card_created_date')->nullable();
            $table->integer('card_created_by')->nullable();
            $table->dateTime('card_modified_date')->nullable();
            $table->integer('card_modified_by')->nullable();
            $table->integer('credit_term')->nullable();
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
        Schema::dropIfExists('customers');
    }
};

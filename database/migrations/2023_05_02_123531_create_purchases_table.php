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
        Schema::create('purchases', function (Blueprint $table) {
            $table->id();
            $table->string('refference_no')->default(0);
            $table->integer('supplier_id')->nullable();
            $table->integer('branch_id')->nullable();
            $table->dateTime('due_date')->nullable();
            $table->decimal('deposit', 11,2)->default(0.00);
            $table->decimal('discount', 11,2)->nullable();
            $table->string('note')->nullable();
            $table->integer('vat')->default(0);
            $table->decimal('total_amount', 11,2)->nullable();
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
        Schema::dropIfExists('purchases');
    }
};

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
        //
        Schema::table('rates' ,function($table) {
            $table->integer('amount_return')->nullable()->after('amount')->change();
            $table->text('description')->nullable()->after('amount_return')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        Schema::table('rates' ,function($table) {
            $table->dropColumn('amount_return')->nullable()->after('amount')->change();
            $table->dropColumn('description')->nullable()->after('amount_return')->change();
        });
    }
};

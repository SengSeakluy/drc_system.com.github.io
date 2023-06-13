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
        Schema::table('users' ,function($table) {
            $table->softDeletes()->nullable()->after('updated_at');
            $table->string('timezone')->nullable()->after('settings');
            $table->integer('created_by')->after('timezone');
            $table->integer('type')->nullable()->default(0);
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
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('timezone');
            $table->dropColumn('created_by');
            $table->dropColumn('type');
        });
    }
};

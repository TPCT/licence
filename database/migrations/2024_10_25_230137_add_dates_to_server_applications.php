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
        Schema::table('server_applications', function (Blueprint $table) {
            $table->timestamp('start_date')->default(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
            $table->timestamp('end_date')->default(\Carbon\Carbon::now()->format('Y-m-d H:i:s'));
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('server_applications', function (Blueprint $table) {
            //
        });
    }
};

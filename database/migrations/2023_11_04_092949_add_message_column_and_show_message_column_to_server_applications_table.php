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
            $table->boolean('show_message')->default(true);
            $table->string('message')
                ->default('
DEV: TPCT
FACEBOOK: https://www.facebook.com/taylor.ackerley.9/
MOBILE: +201094950765');
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
            $table->dropColumn(['show_message', 'message']);
        });
    }
};

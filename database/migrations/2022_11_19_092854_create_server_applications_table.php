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
        Schema::create('server_applications', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(\App\Models\LicenceUser::class);
            $table->foreignIdFor(\App\Models\Server::class);
            $table->foreignIdFor(\App\Models\Application::class);
            $table->boolean('active')->default(false)->nullable(false);
            $table->date('licence_date')->nullable(false);
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
        Schema::dropIfExists('server_applications');
    }
};

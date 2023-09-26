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
        Schema::create('request_status_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('request_id');
            $table->foreignId('user_id');
            $table->tinyInteger('status_before');
            $table->tinyInteger('status_after');
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
        Schema::dropIfExists('request_status_metrics');
    }
};

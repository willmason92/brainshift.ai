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
        Schema::table('request_share_metrics', function (Blueprint $table) {
            $table->dropForeign('request_share_metrics_installer_id_foreign');
            $table->foreign('installer_id')->references('id')->on('experts')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('request_share_metrics', function (Blueprint $table) {
            $table->dropForeign('request_share_metrics_installer_id_foreign');
            $table->foreign('installer_id')->references('id')->on('users')->nullable()->change();
        });
    }
};

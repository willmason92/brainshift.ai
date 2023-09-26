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
        // Requests
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('installer_id')->nullable()->change();
        });
        Schema::table('request_share_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('request_status_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Shed Solutions & Metrics
        Schema::table('shed_solutions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });
        Schema::table('shed_solution_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable()->change();
        });

        // Farmers Library
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('author')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Requests
        Schema::table('requests', function (Blueprint $table) {
            $table->unsignedBigInteger('installer_id')->nullable(false)->change();
        });
        Schema::table('request_share_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
        Schema::table('request_status_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        // Shed Solutions & Metrics
        Schema::table('shed_solutions', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });
        Schema::table('shed_solution_metrics', function (Blueprint $table) {
            $table->unsignedBigInteger('user_id')->nullable(false)->change();
        });

        // Farmers Library
        Schema::table('posts', function (Blueprint $table) {
            $table->unsignedBigInteger('author')->nullable(false)->change();
        });
    }
};

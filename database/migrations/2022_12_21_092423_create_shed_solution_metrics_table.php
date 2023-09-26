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
        Schema::create('shed_solution_metrics', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shed_solution_id');
            $table->foreignId('installer_id')->nullable();
            $table->string('email')->nullable();
            $table->timestamps();

//            $table->unsignedBigInteger('entity_id');
//            $table->string('entity_type');
//            $table->string('change_type')->nullable(); // create or edit shed solution
//            $table->string('user_type')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shed_solution_metrics');
    }
};

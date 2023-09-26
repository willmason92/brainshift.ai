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
        Schema::create('shed_solution_goals', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('shed_solution_id');
            $table->foreign('shed_solution_id')
                ->references('id')
                ->on('shed_solutions');
            $table->unsignedBigInteger('goal_id');
            $table->foreign('goal_id')
                ->references('id')
                ->on('goals');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('shed_solution_goals');
    }
};

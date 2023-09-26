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
        Schema::table('shed_solution_goals', function (Blueprint $table) {
            $table->dropForeign(['shed_solution_id']);
            $table->foreign('shed_solution_id')
                ->references('id')->on('shed_solutions')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('shed_solution_goals', function (Blueprint $table) {
//            $table->dropForeign(['shed_solution_id']);
        });
    }
};

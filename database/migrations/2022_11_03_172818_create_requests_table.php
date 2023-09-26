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
        Schema::create('requests', function (Blueprint $table) {
            $table->id();
            $table->tinyInteger('request_status');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('installer_id')->constrained('experts');
            $table->string('message', 1000);
            $table->string('contact_phone', 20)->nullable(); //needs encrypting
            $table->string('contact_email', 50)->nullable(); //needs encrypting
            $table->foreignId('shed_solution_id')->nullable()->constrained('shed_solutions');
            $table->tinyInteger('project_type')->nullable();
            $table->foreignId('sector_id')->nullable()->constrained();
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
        Schema::dropIfExists('requests');
    }
};

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
        Schema::create('sector_user_expert', function (Blueprint $table) {
            $table->id();
            $table->foreignId('expert_id')->nullable()->constrained('experts')->onDelete('CASCADE');
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('CASCADE');
            $table->foreignId('sector_id')->constrained('sectors')->onDelete('CASCADE');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sector_user_expert');
    }
};

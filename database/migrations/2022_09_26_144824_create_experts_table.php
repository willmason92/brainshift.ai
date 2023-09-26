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
        Schema::create('experts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained('users');
            $table->string('company_name');
            $table->string('company_email')->nullable();
            $table->unsignedBigInteger('location_id')->nullable();
            $table->unsignedTinyInteger('nationwide');
            $table->text('description')->nullable();
            $table->unsignedBigInteger('region_id')->nullable();
            $table->string('expert_url')->nullable();
            $table->foreignId('logo')->nullable()->constrained('files');
            $table->unsignedBigInteger('expert_type_id');

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
        Schema::dropIfExists('experts');
    }
};

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
        Schema::create('materials', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('product_line');
            $table->unsignedBigInteger('image');
            $table->foreign('image')
                ->references('id')
                ->on('files');
            $table->unsignedBigInteger('colour')->nullable();
            $table->foreign('colour')
                ->references('id')
                ->on('colours');
            $table->string('shop_url');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('materials');
    }
};

<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shed_solutions', function (Blueprint $table) {
            $table->id();
            $table->string('project_type_id'); // 0|1 New Build | Refurbishment
            $table->unsignedBigInteger('sector_id');
            $table->foreign('sector_id')
                ->references('id')
                ->on('sectors');
            $table->integer('length');
            $table->integer('width');
            $table->tinyInteger('size_type_id'); // 0|1 meter or feet
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')
                ->references('id')
                ->on('users');
            $table->unsignedBigInteger('project_file_id');
            $table->foreign('project_file_id')
                ->references('id')
                ->on('files');
            $table->string('title');
            $table->integer('age')->nullable();
            $table->tinyInteger('responsibility_id')->nullable(); // 0|1|2 Installer | DIY | Not Known
            $table->tinyInteger('frame_type')->nullable(); // 0|1|2 Wooden Frame | Metal Frame | Other
//            $table->string('np_name')->nullable();
//            $table->string('np_image')->nullable();
            $table->text('np_json_config');
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
        Schema::dropIfExists('shed_solutions');
    }
};

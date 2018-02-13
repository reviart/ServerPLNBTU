<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('ext');
            $table->string('size');
            $table->string('path')->nullable();
            $table->integer('access_permission')->nullable();
            $table->enum('status', ['edited', 'not_edited']);
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('bidang_id');
            $table->unsignedInteger('folder_id');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('bidang_id')->references('id')->on('bidangs');
            $table->foreign('folder_id')->references('id')->on('folders');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class NewcreateTableUserPersonal extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_personal', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->string('phone',100)->nullable();
            $table->string('name',100)->nullable();
            $table->string('family',100)->nullable();
            $table->string('otchestvo',100)->nullable();
            $table->string('nickname',100)->nullable();
            $table->char('pol', 1)->nullable();
            $table->timestamp('bithday')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_personal');
    }
}

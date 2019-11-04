<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Createusercontragents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_contragents', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('user_id')->index();
            $table->char('type', 1)->nullable();
            $table->string('inn',20)->nullable();
            $table->string('kpp',20)->nullable();
            $table->string('name',150)->nullable();
            $table->string('okpo',20)->nullable();
            $table->string('uraddress')->nullable();
            $table->string('bik',20)->nullable();
            $table->string('bankname')->nullable();
            $table->string('bankcity',50)->nullable();
            $table->string('korr_sch',20)->nullable();
            $table->string('rasch_sch',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('user_contragents');
    }
}

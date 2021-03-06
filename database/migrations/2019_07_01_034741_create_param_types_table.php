<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateParamTypesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('param_type', function (Blueprint $table) {
            $table->string('id', 11);
            $table->string('name', 100)->default('');
            $table->string('table', 50);
            $table->integer('kod_sort')->default(0);
            $table->text('description')->nullable();
            $table->primary(['id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('param_type');
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_1s')->unsigned();
            $table->string('name');
            $table->integer('sort_order')->unsigned();
            $table->boolean('status')->default(0);
            $table->string('filter_type', 10)->nullable();
            $table->string('param_type_id', 11)->nullable();
            $table->string('mark', 10)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters');
    }
}

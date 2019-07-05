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
            $table->increments('id');
            $table->integer('id_1s')->unsigned();
            $table->string('name');
            $table->integer('sort_order')->unsigned();
            $table->boolean('status')->default(0);
            $table->string('filter_field', 30)->default('');
            $table->string('filter_type', 10)->default('');
            $table->string('param_type_id', 11)->default('');
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

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFiltersDiapTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('filters_diap', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('filters_id');
            $table->decimal('value1', 15, 3);
            $table->string('descr1', 50);
            $table->decimal('value2', 15, 3);
            $table->string('descr2', 50);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('filters_diap');
    }
}

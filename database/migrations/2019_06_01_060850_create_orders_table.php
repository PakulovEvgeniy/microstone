<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('id_1s')->unsigned();
            $table->string('name');
            $table->integer('sort_order')->unsigned();
            $table->boolean('status');
            $table->string('sort_ord', 10)->nullable();
            $table->string('sort_type', 10)->nullable();
            $table->string('param_type_id', 11)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}

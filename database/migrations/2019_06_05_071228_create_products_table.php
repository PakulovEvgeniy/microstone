<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('id_1s', 100)->UNIque();
            $table->string('parent_id', 100)->index();
            $table->string('sku', 50);
            $table->string('image', 100);
            $table->boolean('status');
            $table->bigInteger('sort_order')->unsigned();
            $table->bigInteger('popul')->default(0);
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
        Schema::dropIfExists('products');
    }
}

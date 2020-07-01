<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDiscountPriceGroupTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('discount_price_group', function (Blueprint $table) {
            $table->integer('id');
            $table->string('name', 100)->default('');
            $table->string('group', 100)->index();
            $table->integer('qty');
            $table->decimal('discount', 10, 1);
            $table->boolean('status')->default(true);
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
        Schema::dropIfExists('discount_price_group');
    }
}

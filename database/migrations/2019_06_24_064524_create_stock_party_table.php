<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateStockPartyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_party', function (Blueprint $table) {
            $table->string('product_id1s', 100)->index();
            $table->string('party_id1s', 100)->index();
            $table->integer('stock')->default(0);
            $table->primary(['product_id1s', 'party_id1s']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_party');
    }
}

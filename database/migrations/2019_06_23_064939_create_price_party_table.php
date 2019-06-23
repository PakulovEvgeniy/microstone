<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePricePartyTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('price_party', function (Blueprint $table) {
            $table->string('product_id1s', 100)->index();
            $table->string('party_id1s', 100)->index();
            $table->tinyInteger('party_type')->default(1);
            $table->integer('min_qty')->default(1);
            $table->decimal('price', 15, 5)->default(0);
            $table->boolean('status')->default(true);
            $table->primary(['product_id1s', 'party_id1s', 'min_qty']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('price_party');
    }
}

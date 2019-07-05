<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePartyParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('party_params', function (Blueprint $table) {
            $table->string('product_id1s', 100)->index();
            $table->string('party_id1s', 100)->index();
            $table->string('param_type_id', 11);
            $table->string('value', 100)->default('');
            $table->primary(['product_id1s','party_id1s', 'param_type_id']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('party_params');
    }
}

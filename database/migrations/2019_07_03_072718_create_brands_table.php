<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('brands', function (Blueprint $table) {
            $table->string('id', 100);
            $table->string('name', 50);
            $table->string('full_name', 100);
            $table->string('cite', 100)->default('');
            $table->text('comment')->nullable();
            $table->string('logo', 100)->default('');
            $table->integer('kod_sort')->default(0);
            $table->boolean('status')->default(0);
            $table->timestamps();
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
        Schema::dropIfExists('brands');
    }
}

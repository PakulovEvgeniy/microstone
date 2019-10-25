<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnToUserContragents extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_contragents', function (Blueprint $table) {
            $table->string('okpo',20)->nullable();
            $table->string('uraddress')->nullable();
            $table->string('bik',20)->nullable();
            $table->string('bankname')->nullable();
            $table->string('bankcity',50)->nullable();
            $table->string('korr_sch',20)->nullable();
            $table->string('rasch_sch',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_contragents', function (Blueprint $table) {
            $table->dropColumn('rasch_sch');
            $table->dropColumn('korr_sch');
            $table->dropColumn('bankcity');
            $table->dropColumn('bankname');
            $table->dropColumn('bik');
            $table->dropColumn('uraddress');
            $table->dropColumn('okpo');
        });
    }
}

<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class UpdateLocalidadesGeo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('localidades', function(Blueprint $table){
            $table->increments('id');
            $table->string('lat',100)->nullable();
            $table->string('lon',100)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('localidades', function(Blueprint $table){
            $table->dropColumn(['lat','lon']);
        });
    }
}

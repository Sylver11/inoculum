<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateInoculumTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('patients', function (Blueprint $table) {
            $table->increments('id');
	    $table->string('firstname');
	    $table->string('secondname');
	    $table->string('email');
            $table->unique('email');
            $table->string('vaccine');
            $table->timestamps();
        });
        Schema::create('bookings', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('patient_id')->unsigned();
            $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');
	    $table->integer('number');
	    $table->string('location');
	    $table->dateTime('time');
            $table->string('vaccine');
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
	
        Schema::table('bookings', function(Blueprint $table) {
            $table->dropForeign('bookings_patient_id_foreign');
            $table->dropIndex('bookings_patient_id_index');
            $table->dropColumn('patient_id');
        }); 
        Schema::dropIfExists('patients');
        Schema::dropIfExists('bookings');
     
    }
}

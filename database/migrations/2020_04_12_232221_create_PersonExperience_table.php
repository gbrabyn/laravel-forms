<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePersonExperienceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('PersonExperience', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('sessionId', 64);
            $table->dateTime('lastEdit');
            $table->string('fullName', 255);
            $table->string('email', 255);
            $table->string('address', 512)->nullable();
            $table->integer('countryId', false, false);
            $table->json('languages');
            $table->json('additionalLanguages');
            $table->json('experience');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('PersonExperience');
    }
}

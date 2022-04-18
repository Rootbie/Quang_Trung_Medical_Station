<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinationRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccination_records', function (Blueprint $table) {
            // Primary key
            $table->id();

            $table->date('first_dose')->nullable();
            $table->date('second_dose')->nullable();
            $table->date('third_dose')->nullable();
            $table->date('additional_dose')->nullable();
            $table->date('booster_dose')->nullable();

            // Foreign key
            $table->unsignedBigInteger('vaccine_id');
            $table->unsignedBigInteger('appointment_id');

            $table->foreign('vaccine_id')->references('id')->on('vaccines');
            $table->foreign('appointment_id')->references('id')->on('appointments');

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
        Schema::dropIfExists('vaccination_records');
    }
}

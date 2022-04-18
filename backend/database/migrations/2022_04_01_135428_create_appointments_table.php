<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            // Primary key
            $table->id();

            $table->date('date');
            $table->decimal('total_price', 12, 2, true)->nullable();
            $table->tinyInteger('status');

            // Foreign key
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('patient_group_id');
            $table->unsignedBigInteger('physician_id');

            $table->foreign('patient_id')->references('id')->on('patients');
            $table->foreign('patient_group_id')->references('id')->on('patient_groups');
            $table->foreign('physician_id')->references('id')->on('users');
            
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
        Schema::dropIfExists('appointments');
    }
}

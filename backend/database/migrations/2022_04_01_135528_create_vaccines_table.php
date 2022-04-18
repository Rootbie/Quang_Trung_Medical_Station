<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVaccinesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vaccines', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('diseases');
            $table->string('lot_number')->nullable();
            $table->integer('number_of_doses')->nullable();
            $table->integer('interval_between_doses(week)')->nullable();
            $table->date('starting_time_of_doses')->nullable();
            $table->decimal('price', 12, 2, true);

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
        Schema::dropIfExists('vaccines');
    }
}

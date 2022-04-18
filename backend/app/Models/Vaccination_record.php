<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccination_record extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'appointment_id', 'vaccine_id', 'first_dose', 'second_dose', 'third_dose', 'additional_dose', 'booster_dose'
    ];
}

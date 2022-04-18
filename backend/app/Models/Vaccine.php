<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Vaccine extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'diseases', 'lot_number', 'number_of_doses', 'interval_between_doses(week)', 'starting_time_of_doses', 'price'
    ];

    /**
     * The appointments belongs to vaccine
     */
    public function appointments()
    {
        return $this->belongsToMany(Appointment::class, 'vaccination_records', 'vaccine_id', 'appointment_id')
            ->withPivot('first_dose', 'second_dose', 'third_dose', 'additional_dose', 'booster_dose');
    }
}

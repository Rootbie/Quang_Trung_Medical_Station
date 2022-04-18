<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'date', 'status', 'total_price', 'patient_id', 'patient_group_id', 'physician_id'
    ];

    /**
     * Get the patient group that owns the appointment
     */
    public function patient_group()
    {
        return $this->belongsTo(Patient_group::class, 'patient_group_id');
    }

    /**
     * The vaccines belongs to appointment
     */
    public function vaccines()
    {
        return $this->belongsToMany(Vaccine::class, 'vaccination_records', 'appointment_id', 'vaccine_id')
            ->withPivot('first_dose', 'second_dose', 'third_dose', 'additional_dose', 'booster_dose');
    }
}

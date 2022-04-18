<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient_group extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'discount_percentage'
    ];

    /**
     * Get the appointments for a patient group
     */
    public function appointments()
    {
        return $this->hasMany(Appointment::class, null);
    }
}

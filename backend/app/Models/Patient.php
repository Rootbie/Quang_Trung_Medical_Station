<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'date_of_birth', 'gender', 'address', 'phone_number', 'weight(kg)', 'height(cm)'
    ];

    /**
     * The physicians belong to patient.
     */
    public function physicians()
    {
        return $this->belongsToMany(User::class, 'appointments', 'patient_id', 'physician_id')
            ->withPivot('date', 'total_price', 'status');
    }
}

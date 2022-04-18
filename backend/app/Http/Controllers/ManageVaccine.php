<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;

class ManageVaccine extends Controller
{
    public function getById($id)
    {
        return Vaccine::find($id);
    }

    public function getAll()
    {
        return Vaccine::all();
    }
}

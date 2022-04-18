<?php

namespace App\Http\Controllers;

use App\Models\Patient;
use Illuminate\Http\Request;

class ManagePatient extends Controller
{

    /**
     * Display the specified patient.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        return Patient::find($id);
    }

    /**
     * Create a new patient instance
     * 
     * @param  Request  $request
     * @return Patient $patient
     */
    public function addPatient(Request $request)
    {
        // Validation
        // Create
        $patient = new Patient;

        $patient->name = $request->input('name');
        $patient->date_of_birth = $request->input('date_of_birth');
        $patient->gender = $request->input('gender');
        $patient->address = $request->input('address');
        $patient->phone_number = $request->input('phone_number');

        if (isset($request->weight)) {
            $patient['weight(kg)'] = $request->input('weight');
        }

        if (isset($request->height)) {
            $patient['height(cm)'] = $request->input('height');
        }

        $patient->save();

        return $patient;
    }

    /**
     * Update the specified patient in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updatePatient(Request $request, $id)
    {
        $patient = Patient::find($id);

        if ($patient) {
            $patient->name = $request->input('name');
            $patient->date_of_birth = $request->input('date_of_birth');
            $patient->gender = $request->input('gender');
            $patient->address = $request->input('address');
            $patient->phone_number = $request->input('phone_number');

            if (isset( $request['weight(kg)'] )) {
                $patient['weight(kg)'] = (float) $request->input('weight(kg)');
            }

            if (isset( $request['height(cm)'] )) {
                $patient['height(cm)'] = (float) $request->input('height(cm)');
            }

            $patient->save();

            return response(['message' => 'Update patient successfully']);
        }

        return response(['message' => 'Patient not found']);
    }
}

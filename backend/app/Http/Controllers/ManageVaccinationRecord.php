<?php

namespace App\Http\Controllers;

use App\Models\Appointment;
use App\Models\Vaccination_record;
use Illuminate\Http\Request;

class ManageVaccinationRecord extends Controller
{
    /**
     * Display the specified vaccination record of a patient.
     *
     * @param  int  $patient_id
     * @return \Illuminate\Http\Response
     */
    public function getById($patient_id)
    {
        $data = Appointment::where('patient_id', $patient_id)->get();
        if ($data) {
            foreach ($data as $item) {
                $item->vaccines;
            }
            return $data;
        }

        return response(['message' => 'Vaccination record not found']);
    }


    /**
     * Create a new vaccination record instance
     * 
     * @param  Request  $request
     * @param  int  $patient_id
     * @return Response
     */
    public function addRecord(Request $request, $patient_id)
    {
        // Insert into appointment table
        $appointment = Appointment::create([
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'total_price' => $request->input('total_price'),
            'patient_id' => $patient_id,
            'patient_group_id' => $request->input('patient_group_id'),
            'physician_id' => $request->input('physician_id')
        ]);

        // Insert into vaccination records table
        foreach ($request->input('vaccines') as $vaccine) {
            Vaccination_record::create([
                'appointment_id' => $appointment->id,
                'vaccine_id' => $vaccine['vaccine_id']
            ]);
        }

        return response(['message' => 'Book a vaccination successfully']);
    }

    /**
     * Update the specified vaccination record in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updateRecord(Request $request, $id)
    {
        $data = Vaccination_record::find($id);

        if ($data) {
            if (isset($request['first_dose'])) {
                $data->first_dose = $request->input('first_dose');
            }

            if (isset($request['second_dose'])) {
                $data->second_dose = $request->input('second_dose');
            }

            if (isset($request['third_dose'])) {
                $data->third_dose = $request->input('third_dose');
            }

            if (isset($request['additional_dose'])) {
                $data->additional_dose = $request->input('additional_dose');
            }

            if (isset($request['booster_dose'])) {
                $data->booster_dose = $request->input('booster_dose');
            }

            $data->save();
            return response(['message' => 'Update vaccination record successfully']);
        }

        return response(['message' => 'Vaccination record not found']);
    }
}

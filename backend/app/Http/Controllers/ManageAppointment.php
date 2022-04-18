<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\Appointment;
use App\Models\Patient_group;
use App\Models\Vaccination_record;
use App\User;
use stdClass;

class ManageAppointment extends Controller
{
    /**
     * Display the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function getById($id)
    {
        $data = Appointment::find($id);
        if ($data) {
            $data->vaccines;

            // $data->patient_group;
            // $data->physician_id;
            // $data->physician = User::where('id', $data->physician_id)->get(['name', 'phone_number'])->first();

            return $data;
        }

        return response(['message' => 'Appointment not found']);
    }

    /**
     * Display a listing of the appointments.
     *
     * @return object
     */
    public function getAll()
    {
        return Appointment::all();
    }

    /**
     * Show the form for creating a new appointment.
     *
     * @return JSON
     */
    public function create()
    {
        $info = new stdClass();
        $info->patient_groups = Patient_group::all();
        $info->physicians = User::get(['id', 'name'])->except([1]);

        // get vaccines info
        $manageVaccine = new ManageVaccine;
        $info->vaccines = $manageVaccine->getAll();

        return json_encode($info);
    }

    /**
     * Store a new appointment instance in storage
     * 
     * @param  Request  $request
     * @return Response
     */
    public function addAppointment(Request $request)
    {
        // appointment : patient + patient group('service'->'total_price', 'COVID-19 vaccination' -> 0)
        // date
        // price/1 dose * quantity -> subtotal_price
        // total_price = subtotal_price - subtotal_price * discount%  

        // Insert into patient table 
        $managePatient = new ManagePatient;
        $newPatient = $managePatient->addPatient($request);

        // Insert into appointment table
        $appointment = Appointment::create([
            'date' => $request->input('date'),
            'status' => $request->input('status'),
            'total_price' => $request->input('total_price'),
            'patient_id' => $newPatient->id,
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
     * Show the form for editing the specified appointment.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified appointment in storage.
     *
     * @param  Request  $request
     * @param  int  $id
     * @return Response
     */
    public function updateAppointment(Request $request, $id)
    {
        $data = Appointment::find($id);

        if ($data) {

            // Update only if appointment status is not checked
            if ($data->status === 0) {

                $data->date = $request->input('date');
                $data->status = $request->input('status');
                $data->total_price = $request->input('total_price');
                $data->physician_id = $request->input('physician_id');
                $data->patient_group_id = $request->input('patient_group_id');

                if ( isset($request['vaccines'] )) {

                    // Update Vaccination record
                    $data->vaccines()->detach(); // Delete the vaccine records out of the intermediate table

                    foreach ($request->input('vaccines') as $vaccineReq) {

                        // Attach one/more vaccine_id to an appointment_id 
                        $data->vaccines()->attach($id, [
                            'vaccine_id' => $vaccineReq['vaccine_id']
                        ]);
                    }
                }

                // Save data to appointment table
                $data->save();

                return response(['message' => 'Update appointment successfully']);
            } else {
                return response(['message' => 'Not allow to update this appointment']);
            }
        }

        return response(['message' => 'Appointment not found']);
    }

    /**
     * Remove the specified appointment from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteAppointment($id)
    {
        $data = Appointment::find($id);

        if ($data) {
            $data->vaccines()->detach();
            $data->delete();

            return response(['message' => 'Delete successfully']);
        }

        return response(['message' => 'Appointment not found']);
    }
}

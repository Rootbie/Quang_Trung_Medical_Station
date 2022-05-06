<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Vaccine;
use Illuminate\Support\Facades\Validator;

class ManageVaccine extends Controller
{
    public function getById($id)
    {
        $vaccine = Vaccine::find($id);

        if ($vaccine) {
            return $vaccine;
        }

        return response(['message' => 'Vaccine not found']);
    }

    public function getAll()
    {
        return Vaccine::all();
    }

    /**
     * Store a new vaccine instance in storage
     * 
     * @param  Request  $request
     * @return Response
     */
    public function addVaccine(Request $request)
    {
        // Part 1: Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'diseases' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        // Part 2: Create vaccine
        Vaccine::create([
            'name' => $request->input('name'),
            'diseases' => $request->input('diseases'),
            'lot_number' => $request->input('lot_number'),
            'number_of_doses' => (int) $request->input('number_of_doses'),
            'interval_between_doses(week)' => (int) $request->input('interval_between_doses'),
            'starting_time_of_doses' => $request->input('starting_time_of_doses'),
            'price' => (float) $request->input('price')
        ]);

        return response(['message' => 'Add vaccine successfully']);
    }


    /**
     * Update the specified vaccine in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateVaccine(Request $request, $id)
    {
        // Validate data
        $check = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'diseases' => 'required|string',
            'price' => 'required|numeric'
        ]);

        if ($check->fails()) {
            return response(['message' => $check->errors()->all()], 422);
        }

        // Find vaccine
        $vaccine = Vaccine::find($id);

        if ($vaccine) {

            $vaccine->name = $request->input('name');
            $vaccine->diseases = $request->input('diseases');

            if (isset($request['lot_number'])) {
                $vaccine->lot_number =  $request->input('lot_number');
            }

            if (isset($request['number_of_doses'])) {
                $vaccine->number_of_doses = (int) $request->input('number_of_doses');
            }

            if (isset($request['interval_between_doses(week)'])) {
                $vaccine['interval_between_doses(week)'] = (int) $request->input('interval_between_doses(week)');
            }

            if (isset($request['starting_time_of_doses'])) {
                $vaccine->starting_time_of_doses =  $request->input('starting_time_of_doses');
            }

            $vaccine->price = (float) $request->input('price');

            $vaccine->save();
            return response(['message' => 'Update successfully']);
        }

        return response(['message' => 'Vaccine not found']);
    }

    /**
     * Remove the specified vaccine from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function deleteVaccine($id)
    {
        $vaccine = Vaccine::find($id);

        if ($vaccine) {
            $vaccine->delete();
            return response(['message' => 'Delete successfully']);
        }

        return response(['message' => 'Vaccine not found']);
    }
}

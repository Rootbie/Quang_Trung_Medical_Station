<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ManagePhysician extends Controller
{
    public function user($id)
    {
        $physician = User::find($id);

        if ($physician) {
            $physician->img_src = asset('images/' . $physician->img_src);

            return $physician;
        }

        return response(['message' => 'Physician not found'], 404);
    }

    public function listUser()
    {
        return User::all();
    }

    public function addUser(Request $request)
    {
        // Part 1 : Validate
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => array('required', 'size:10', 'regex:/((09|03|07|08|05)+([0-9]{8})\b)/', 'unique:users'),
            'password' => 'required|string|min:8',
            'password_confirmation' => 'required|same:password',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'ID_card_number' => 'required|numeric|unique:users'
        ]);

        if ($validator->fails()) {
            return response(['message' => $validator->errors()->all()], 422);
        }

        // Part 2: Create user
        $renameImage = '';

        if ($request->hasFile('img_src')) {
            $image = $request->file('img_src');
            $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
            $renameImage = $fileName . '_' . time() . '.' . $image->getClientOriginalExtension();
            $dest = public_path('/images');
            $image->move($dest, $renameImage);
        }

        User::create([
            'name' => $request->input('name'),
            'phone_number' => $request->input('phone_number'),
            'password' => Hash::make($request->input('password')),
            'date_of_birth' => $request->input('date_of_birth'),
            'gender' => $request->input('gender'),
            'address' => $request->input('address'),
            'ID_card_number' => $request->input('ID_card_number'),
            'img_src' => $renameImage
        ]);

        return response(['message' => 'Add user successfully']);
    }

    /**
     * Update the specified physician in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateUser(Request $request, $id)
    {
        // Validate data
        $check = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'phone_number' => array('required', 'size:10', 'regex:/((09|03|07|08|05)+([0-9]{8})\b)/'),
            // 'password' => 'required|string|min:8',
            'date_of_birth' => 'required|date',
            'gender' => 'required|string',
            'address' => 'required|string',
            'ID_card_number' => 'required|numeric'
        ]);

        if ($check->fails()) {
            return response(['message' => $check->errors()->all()], 422);
        }

        // Find user
        $user = User::find($id);

        if ($user) {

            $user->name = $request->input('name');
            $user->phone_number = $request->input('phone_number');
            // $user->password = Hash::make($request->password);
            $user->gender =  $request->input('gender');
            $user->date_of_birth =  $request->input('date_of_birth');
            $user->address =  $request->input('address');
            $user->ID_card_number =  $request->input('ID_card_number');

            if ($request->hasFile('img_src')) {
                $image = $request->file('img_src');
                $fileName = pathinfo($image->getClientOriginalName(), PATHINFO_FILENAME);
                $renameImage = $fileName . '_' . time() . '.' . $image->getClientOriginalExtension();
                $dest = public_path('/images');
                $image->move($dest, $renameImage);

                $user->img_src = $renameImage;
            }

            $user->save();
            return response(['message' => 'Update successfully']);
        }

        return response(['message' => 'User not found']);
    }

    public function deleteUser($id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return response(['message' => 'Delete successfully']);
        }

        return response(['message' => 'User not found']);
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\Student;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AccountController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'matricule' => 'required|string|min:5|max:15',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
            'dob' => 'required|date',
            'marital_status' => 'required',
        ]);

        try {
            $user = $request->user();
            $user->matricule = $request['matricule'];
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->phone = $request['phone'];
            $user->dob = $request['dob'];
            $user->marital_status = $request['marital_status'];
            $user->save();

            if ($request['role'] == "student") {
                $student = new Student();
                $student->user_id = $user->id;
            } else {
                $staff = new Staff();
                $staff->user_id = $user->id;
            }
            //return successful response
            return response()->json(['user' => $user, 'message' => 'User updated'], 200);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User update Failed!'], 409);
        }
    }
}

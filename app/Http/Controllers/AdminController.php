<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class AdminController extends Controller {

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
            'matricule' => 'string|min:5|max:15',
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'password' => 'string|min:8',
            'phone' => 'string|min:9|max:12',
            'dob' => 'date',
        ]);

        try {
            $user = $request->user();
            $user->matricule = strtoupper($request['matricule']);
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->phone = $request['phone'];
            $user->dob = $request['dob'];
            $user->marital_status = $request['marital_status'];
            $user->save();

            //return successful response
            return response()->json(['user' => $user, 'message' => 'User updated'], 200);

        } catch (\Exception $e) {
            //return error message
            error_log('An error occured' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }
}

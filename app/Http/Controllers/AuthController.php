<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

use App\Models\User;
use Exception;

class AuthController extends Controller
{
    /**
     * Register a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validate($request, [
            'matricule' => 'required|string|min:5|max:15|unique:users',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
            'dob' => 'required|date',
            'gender' => 'required',
            'marital_status' => 'required',
        ]);

        try {
            $user = new User;
            $user->matricule = strtoupper($request['matricule']);
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->phone = $request['phone'];
            $user->dob = $request['dob'];
            $user->gender = $request['gender'];
            $user->marital_status = $request['marital_status'];
            $user->role = "admin";
            $user->save();
            //return successful response
            return response()->json(['user' => $user, 'message' => 'new User Created'], 200);

        } catch (Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }
    }

    /**
     * Login a user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        //validate incoming request
        $this->validate($request, [
            'username' => 'required|string',
            'password' => 'required|string',
            'role' => 'required|string',
        ]);

        try {
            if (filter_var($request->input('username'), FILTER_VALIDATE_EMAIL)) {
                $type = 'email';
                $user = User::where('email', $request->input('username'))->first();
            }else {
                $type = 'matricule';
                $user = User::where('matricule', strtoupper($request->input('username')))->first();
            }

            $request->merge([$type => $request->input('username')]);

            // Verify the password and generate the token
            if (Hash::check($request['password'], $user->password)) {
                $credentials = $request->only([$type, 'password']);

                if (!$token = Auth::attempt($credentials)) {
                    return response()->json(['message' => 'Unauthorized'], 401);
                }
                return $this->respondWithToken($user, $token);
            }
        } catch (\Exception $e) {
            error_log('An error occured' . $e);
            return response()->json(['message' => 'User not found', 'logs' => $e], 404);
        }
    }
}

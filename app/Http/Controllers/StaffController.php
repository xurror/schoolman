<?php

namespace App\Http\Controllers;

use App\Models\Staff;
use App\Models\User;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StaffController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allstaff()
    {
        $staff = DB::table('users')
                        ->join('staff', 'users.id', '=', 'staff.user_id')
                        ->join('departments', 'departments.id', '=', 'staff.department_id')
                        ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
                        ->select('users.*', 'staff.nature_of_job', 'staff.basic_pay', 'staff.dh', 'departments.name', 'faculties.name')
                        ->get();
        return response()->json(['user' => $staff, 'message' => 'All students and details'], 200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
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
            'department' => 'required',
            'nature_of_job' => 'required|string|max:255',
            'basic_pay' => 'required|string|max:255',
        ]);

        try {
            $staff = new User();
            $staff->matricule = $request['matricule'];
            $staff->name = $request['name'];
            $staff->email = $request['email'];
            $staff->password = Hash::make($request['password']);
            $staff->phone = $request['phone'];
            $staff->dob = $request['dob'];
            $staff->gender = $request['gender'];
            $staff->marital_status = $request['marital_status'];
            $staff->role = "staff";
            $staff->save();

            $department_id = DB::table('departments')->select('id')
                                ->where('name', $request['department'])->first();

            $staff_details = new Staff([
                'department_id' => $department_id,
                'nature_of_job' => $request['nature_of_job'],
                'basic_pay' => $request['basic_pay'],
            ]);

            // $user_id = DB::table('users')->insertGetId([
            //     'matricule' => $request['matricule'],
            //     'name' => $request['name'],
            //     'email' => $request['email'],
            //     'password' => Hash::make($request['password']),
            //     'phone' => $request['phone'],
            //     'dob' => $request['dob'],
            //     'gender' => $request['gender'],
            //     'marital_status' => $request['marital_status'],
            //     'role' => "staff",
            // ]);


            // $staff_id = DB::table('staff')->insertGetId([
            //     'user_id' => $user_id,
            //     'department_id' => $department_id,
            //     'nature_of_job' => $request['nature_of_job'],
            //     'basic_pay' => $request['basic_pay'],
            // ]);

            // $staff = DB::table('users')->find($user_id);

            $staff['extra_details'] = $staff_details;

            //return successful response
            return response()->json(['user' => $staff, 'message' => 'new User Created'], 200);

        } catch (Exception $e) {
            //return error message
            return response()->json(['message' => 'User Registration Failed!'], 409);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        User::findOrFail($id)->delete();
    }
}

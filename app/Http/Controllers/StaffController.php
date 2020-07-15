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
    public function all()
    {
        try{
            $staff_list = array();
            $staff =  DB::table('users')->where('role', 'staff')->get();

            foreach($staff as $staffm) {
                $staff_extras = DB::table('staff')->where('user_id', $staffm->id)->first();
                $department = DB::table('departments')->where('id', $staff_extras->department_id)->first();
                $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

                $staff_details = (object) [
                    "id" => $staffm->id,
                    "matricule" => strtoupper($staffm->matricule),
                    "name" => $staffm->name,
                    "email" => $staffm->email,
                    "password" => $staffm->password,
                    "phone" => $staffm->phone,
                    "dob"  => $staffm->dob,
                    "gender" => $staffm->gender,
                    "marital_status" => $staffm->marital_status,
                    "nature_of_job" => $staff_extras->nature_of_job,
                    "basic_pay" => $staff_extras->basic_pay,
                    'department' => $department->name,
                    'faculty' => $faculty->name
                ];

                array_push($staff_list, $staff_details);
            }
            return response()->json(['staff' => $staff_list, 'message' => 'All staff and details'], 200);
        } catch (Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Staff update Failed!', 'logs' => $e], 409);
        }

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
            'department_id' => 'required',
            'nature_of_job' => 'required|string|max:255',
            'basic_pay' => 'required|string|max:255',
        ]);

        try {
            $staff = new User();
            $staff->matricule = strtoupper($request['matricule']);
            $staff->name = $request['name'];
            $staff->email = $request['email'];
            $staff->password = Hash::make($request['password']);
            $staff->phone = $request['phone'];
            $staff->dob = $request['dob'];
            $staff->gender = $request['gender'];
            $staff->marital_status = $request['marital_status'];
            $staff->role = "staff";
            $staff->save();

            $staff_extras = new Staff([
                'user_id' => $staff->id,
                'department_id' => $request['department_id'],
                'nature_of_job' => $request['nature_of_job'],
                'basic_pay' => $request['basic_pay'],
            ]);

            $department = DB::table('departments')->where('id', $staff_extras->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $staff_details = (object) [
                "id" => $staff->id,
                "matricule" => strtoupper($staff->matricule),
                "name" => $staff->name,
                "email" => $staff->email,
                "password" => $staff->password,
                "phone" => $staff->phone,
                "dob"  => $staff->dob,
                "gender" => $staff->gender,
                "marital_status" => $staff->marital_status,
                "nature_of_job" => $staff_extras->nature_of_job,
                "basic_pay" => $staff_extras->basic_pay,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['staff' => $staff_details, 'message' => 'new Staff Created'], 200);

        } catch (Exception $e) {
            //return error message
            error_log('An error occured' . $e);
            return response()->json(['message' => 'Staff Registration Failed!', 'logs' => $e], 409);
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
        try {
            $staff = User::findOrFail($id);
            $staff_extras = DB::table('staff')->where('user_id', $staff->id)->first();
            $department = DB::table('departments')->where('id', $staff_extras->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $staff_details = (object) [
                "id" => $staff->id,
                "matricule" => strtoupper($staff->matricule),
                "name" => $staff->name,
                "email" => $staff->email,
                "password" => $staff->password,
                "phone" => $staff->phone,
                "dob"  => $staff->dob,
                "gender" => $staff->gender,
                "marital_status" => $staff->marital_status,
                "nature_of_job" => $staff_extras->nature_of_job,
                "basic_pay" => $staff_extras->basic_pay,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];
            return response()->json(['user' => $staff_details, 'message' => 'staff details'], 200);

        } catch(Exception $e) {
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred', 'logs' => $e], 500);
        }
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
            $staff = User::findOrFail($id);
            $staff->matricule = strtoupper($request['matricule']);
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

            $staff_extras = new Staff([
                'department_id' => $department_id,
                'nature_of_job' => $request['nature_of_job'],
                'basic_pay' => $request['basic_pay'],
            ]);

            $staff->staff()->save($staff_extras);

            $department = DB::table('departments')->where('id', $staff_extras->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $staff_details = (object) [
                "id" => $staff->id,
                "matricule" => strtoupper($staff->matricule),
                "name" => $staff->name,
                "email" => $staff->email,
                "password" => $staff->password,
                "phone" => $staff->phone,
                "dob"  => $staff->dob,
                "gender" => $staff->gender,
                "marital_status" => $staff->marital_status,
                "nature_of_job" => $staff_extras->nature_of_job,
                "basic_pay" => $staff_extras->basic_pay,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['staff' => $staff_details, 'message' => 'Staff updated successfully'], 200);

        } catch (Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Staff update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Staff::findOrFail($id)->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentController extends Controller {

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function allstudents()
    {
        $student = DB::table('users')
                        ->join('students', 'users.id', '=', 'students.user_id')
                        ->join('departments', 'departments.id', '=', 'students.department_id')
                        ->join('faculties', 'faculties.id', '=', 'departments.faculty_id')
                        ->select('users.*', 'students.dor', 'departments.name', 'faculties.name')
                        ->get();
        return response()->json(['user' => $student, 'message' => 'All students and details'], 200);
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
        ]);

        try {
            $student = new User();
            $student->matricule = $request['matricule'];
            $student->name = $request['name'];
            $student->email = $request['email'];
            $student->password = Hash::make($request['password']);
            $student->phone = $request['phone'];
            $student->dob = $request['dob'];
            $student->gender = $request['gender'];
            $student->marital_status = $request['marital_status'];
            $student->role = "student";
            $student->save();

            $student_details = new Student([
                'department_id' => $request['department_id'],
            ]);

            $student->student()->save($student_details);

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

            // $student_id = DB::table('student')->insertGetId([
            //     'user_id' => $user_id,
            //     'department_id' => $request['department_id'],
            //     'basic_pay' => $request['basic_pay'],
            // ]);

            // $staff = DB::table('users')->find($user_id);

            $student['extra_details'] = $student_details;

            //return successful response
            return response()->json(['user' => $student, 'message' => 'new User Created'], 200);

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

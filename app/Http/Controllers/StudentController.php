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
    public function all()
    {
        $student_list = array();
        $students =  DB::table('users')->where('role', 'student')->get();

        foreach($students as $student) {
            $student_details = DB::table('students')->where('user_id', $student->id)->first();
            $department = DB::table('departments')->where('id', $student_details->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $student->id,
                "matricule" => $student->matricule,
                "name" => $student->name,
                "email" => $student->email,
                "password" => $student->password,
                "phone" => $student->phone,
                "dob"  => $student->dob,
                "gender" => $student->gender,
                "marital_status" => $student->marital_status,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            array_push($student_list, $student_details);
        }
        return response()->json(['students' => $student_list, 'message' => 'All students and details'], 200);
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

            $department_id = DB::table('departments')->select('id')
                                ->where('name', $request['department'])->first();

            $student_details = new Student([
                'department_id' => $department_id,
            ]);

            $student->student()->save($student_details);

            $department = DB::table('departments')->where('id', $student_details->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $student->id,
                "matricule" => $student->matricule,
                "name" => $student->name,
                "email" => $student->email,
                "password" => $student->password,
                "phone" => $student->phone,
                "dob"  => $student->dob,
                "gender" => $student->gender,
                "marital_status" => $student->marital_status,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['student' => $student_details, 'message' => 'new Student Created'], 200);

        } catch (Exception $e) {
            //return error message
            return response()->json(['message' => 'Student Registration Failed!'], 409);
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
            $student = User::findOrFail($id);
            $student_details = DB::table('students')->where('user_id', $student->id)->first();
            $department = DB::table('departments')->where('id', $student_details->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $student->id,
                "matricule" => $student->matricule,
                "name" => $student->name,
                "email" => $student->email,
                "password" => $student->password,
                "phone" => $student->phone,
                "dob"  => $student->dob,
                "gender" => $student->gender,
                "marital_status" => $student->marital_status,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];
            return $student_details;
        } catch(Exception $e) {
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred caused by ' . $e], 500);
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
        ]);

        try {
            $student = User::findOrFail($id);
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

            $department_id = DB::table('departments')->select('id')
                                ->where('name', $request['department'])->first();

            $student_details = DB::table('students')->where('user_id', $id);
            $student_details->department_id = $department_id;

            $student->student()->save($student_details);

            $department = DB::table('departments')->where('id', $student_details->department_id)->first();
            $faculty = DB::table('faculties')->where('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $student->id,
                "matricule" => $student->matricule,
                "name" => $student->name,
                "email" => $student->email,
                "password" => $student->password,
                "phone" => $student->phone,
                "dob"  => $student->dob,
                "gender" => $student->gender,
                "marital_status" => $student->marital_status,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['user' => $student_details, 'message' => 'Student updated successfully'], 200);

        } catch (Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Student Update Failed!'], 409);
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
        User::findOrFail($id)->delete();
    }
}

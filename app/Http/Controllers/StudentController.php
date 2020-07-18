<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
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
        try {
            $student_list = array();
            $users =  User::where('role', 'student')->get();

            foreach($users as $user) {
                $student_details = Student::where('user_id', $user->id)->first();
                $department = Department::where('id', $student_details->department_id)->first();
                $faculty = Faculty::where('id', $department->faculty_id)->first();

                $student_details = (object) [
                    "id" => $user->id,
                    "matricule" => strtoupper($user->matricule),
                    "name" => $user->name,
                    "email" => $user->email,
                    "password" => $user->password,
                    "phone" => $user->phone,
                    "dob"  => $user->dob,
                    "doa"  => $student_details->doa,
                    "gender" => $user->gender,
                    "marital_status" => $user->marital_status,
                    'department_id' => $department->id,
                    'department' => $department->name,
                    'faculty_id' => $faculty->id,
                    'faculty' => $faculty->name
                ];
                array_push($student_list, $student_details);
            }
            return response()->json(['students' => $student_list, 'message' => 'All students and details'], 200);
        } catch (\Exception $e) {
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
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
        ]);

        DB::beginTransaction();
        try {

            $user = User::create([
                'matricule' => strtoupper($request['matricule']),
                'name' => $request['name'],
                'email' => $request['email'],
                'password' => Hash::make($request['password']),
                'phone' => $request['phone'],
                'dob' => $request['dob'],
                'gender' => $request['gender'],
                'marital_status' => $request['marital_status'],
                'role' => 'student',
            ]);

            $student = new Student();
            $student->department_id = $request['department_id'];
            $student->doa = date('Y-m-d');
            $user->student()->save($student);

            $department = Department::where('id', $request['department_id'])->first();
            $faculty = Faculty::where('id', $department->faculty_id)->first();
            $student_details = (object) [
                "id" => $user->id,
                "matricule" => strtoupper($user->matricule),
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "phone" => $user->phone,
                "dob"  => $user->dob,
                "doa"  => $student->doa,
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
                'department' => $department->name,
                'faculty_id' => $faculty->id,
                'faculty' => $faculty->name
            ];
            DB::commit();
            return response()->json(['student' => $student_details, 'message' => 'new Student Created'], 200);

        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log($e);
            return response()->json(['message' => 'Student Registration Failed!', 'logs' => $e], 409);
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
            $user = User::findOrFail($id);
            $student = Student::where('user_id', $user->id)->first();
            $department = Department::where('id', $student->department_id)->first();
            $faculty = Faculty::here('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $user->id,
                "matricule" => strtoupper($user->matricule),
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "phone" => $user->phone,
                "dob"  => $user->dob,
                "doa"  => $student->doa,
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
                'department' => $department->name,
                'faculty_id' => $faculty->id,
                'faculty' => $faculty->name
            ];
            return $student_details;
        } catch(\Exception $e) {
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
            'matricule' => 'required|string|min:5|max:15',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
            'dob' => 'required|date',
            'gender' => 'required',
            'marital_status' => 'required',
            'department_id' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $user = User::findOrFail($id);
            $user->matricule = strtoupper($request['matricule']);
            $user->name = $request['name'];
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->phone = $request['phone'];
            $user->dob = $request['dob'];
            $user->gender = $request['gender'];
            $user->marital_status = $request['marital_status'];
            $user->role = "student";
            $user->save();

            $student = Student::where('user_id', $id)->first();
            $student->department_id = $request['department_id'];
            $user->student()->save($student);

            $department = Department::where('id', $student->department_id)->first();
            $faculty = Faculty::where('id', $department->faculty_id)->first();

            $student_details = (object) [
                "id" => $user->id,
                "matricule" => strtoupper($user->matricule),
                "name" => $user->name,
                "email" => $user->email,
                "password" => $user->password,
                "phone" => $user->phone,
                "dob"  => $user->dob,
                "doa"  => $student->doa,
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
                'department' => $department->name,
                'faculty_id' => $faculty->id,
                'faculty' => $faculty->name
            ];

            DB::commit();
            //return successful response
            return response()->json(['user' => $student_details, 'message' => 'Student updated successfully'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Student Update Failed!', 'logs' => $e], 409);
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
        DB::beginTransaction();
        try {
            $user_id = Student::findOrFail($id)->user_id;
            $user = User::find($user_id);
            $user->student()->delete();
            DB::commit();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }
}

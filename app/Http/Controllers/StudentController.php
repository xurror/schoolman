<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\Faculty;
use Illuminate\Http\Request;
use App\Models\Student;
use App\Models\User;
use Exception;
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
                    "gender" => $user->gender,
                    "marital_status" => $user->marital_status,
                    'department_id' => $department->id,
                    'department' => $department->name,
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

        try {
            $user = new User();
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

            $student = new Student(['department_id' => $request['department_id']]);
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
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['student' => $student_details, 'message' => 'new Student Created'], 200);

        } catch (Exception $e) {
            //return error message
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
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
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
            'department_id' => 'required',
        ]);

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
                "gender" => $user->gender,
                "marital_status" => $user->marital_status,
                'department_id' => $department->id,
                'department' => $department->name,
                'faculty' => $faculty->name
            ];

            //return successful response
            return response()->json(['user' => $student_details, 'message' => 'Student updated successfully'], 200);

        } catch (Exception $e) {
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
        try {
            Student::findOrFail($id)->delete();
            return response()->json(['message' => 'Successfully deleted'], 200);
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }
}

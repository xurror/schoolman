<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class StudentAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCourses()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $courses = $student->courses;
        return $courses;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerCourses(Request $request)
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $this->validate($request, [
            'codes' => 'required',
        ]);

        $courses_id = array();

        foreach($request['codes'] as $code) {
            $course_id = Course::where('code', $request['code'])->first()->id;
            array_push($courses_id, $course_id);
        }

        $student->courses->sync($courses_id);
        return response()->json(['message' => 'Courses successfully registered'], 200);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFees()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $fees = $student->fees;
        return $fees;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResults()
    {
        $user = Auth::user();
        $student = Student::where('user_id', $user->id)->first();
        $courses = $student->courses;
        $results = array();

        foreach($courses as $course) {
            $course_student = DB::table('course_student')
                                ->where(['student_id', $student->id],
                                        ['course_id', $course->id])
                                ->first();
            $result = (object) [
                'code' => $course->code,
                'ca_mark' => $course_student->ca_mark,
                'exam_mark' => $course_student->exam_mark,
                'grade' => $course_student->grade,
            ];
            array_push($results, $result);
        }
        return response()->json(['Results' => $results, 'message' => 'Student results'], 200);
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

            //return successful response
            return response()->json(['user' => $user, 'message' => 'User updated'], 200);

        } catch (\Exception $e) {
            //return error message
            return response()->json(['message' => 'User update Failed!'], 409);
        }
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class StaffAccountController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getCourses()
    {
        $staff_user = Auth::user();
        $staff = Staff::where('user_id', $staff_user->id)->first();
        $courses = $staff->courses;
        $my_courses = array();

        try {
            foreach($courses as $course) {
                error_log($course->id);
                $course_students = CourseStudent::where('course_id', $course->id)->get();
                // error_log($course_students->id);
                $course_details = (object) [
                    'students' => $course_students,
                    'code' => strtoupper($course->code),
                    'title' => $course->title,
                    'credits' => $course->credits,
                ];
                array_push($my_courses, $course_details);
            }
            return $my_courses;
        } catch (\Exception $e) {
            error_log('An error occurred caused by \n' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
        }

    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerMarks(Request $request)
    {
        $this->validate($request, [
            'marks' => 'required',
        ]);

        try {
            foreach ($request['marks'] as $mark) {
                $user = User::where('matricule', strtoupper($mark['matricule']))->first();
                $student = Student::where('user_id', $user->id)->first();
                $course = Course::where('code', strtoupper($mark['code']))->first();

                $course_student = CourseStudent::where('student_id', $student->id)
                                                ->where('course_id', $course->id)
                                                ->first();
                $course_student->ca_mark = $mark['ca_mark'];
                $course_student->exam_mark = $mark['exam_mark'];
                $course_student->grade = $mark['grade'];
                $course_student->save();
            }
            return response()->json(['message' => 'Successfully registered marks'], 200);
        } catch (\Exception $e) {
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'string|email|max:255',
            'password' => 'string|min:8',
            'phone' => 'required|string|min:9|max:12',
        ]);

        try {
            $staff = $request->user();
            $staff->email = $request['email'];
            $staff->password = Hash::make($request['password']);
            $staff->phone = $request['phone'];
            $staff->marital_status = $request['marital_status'];
            $staff->save();

            //return successful response
            return response()->json(['staff' => $staff, 'message' => 'Staff updated successfully'], 200);

        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Staff update Failed!', 'logs' => $e], 409);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
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
                $course_students_list = array();
                foreach ($course_students as $course_student) {
                    $student = Student::findOrFail($course_student->student_id);
                    $user = (object) [
                        'matricule' => User::findOrFail($student->user_id)->matricule,
                        'student_id' => $student->id,
                        'ca_mark' => $course_student->ca_mark,
                        'exam_mark' => $course_student->exam_mark,
                        'grade' => $course_student->grade
                    ];
                    array_push($course_students_list, $user);
                }
                $course_details = (object) [
                    'course_id' => $course->id,
                    'code' => strtoupper($course->code),
                    'title' => $course->title,
                    'credits' => $course->credits,
                    'students' => $course_students_list,
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
            'marks' => 'required|array',
        ]);

        DB::beginTransaction();
        try {
            foreach ($request['marks'] as $mark) {
                $user = User::where('matricule', strtoupper($mark['matricule']))->first();

                if ($user == NULL) {
                    return response()->json(['message' => 'Student matricule not found for this course'], 404);
                }

                $student = Student::where('user_id', $user->id)->first();
                $course = Course::where('code', strtoupper($mark['code']))->first();

                if ($course == NULL) {
                    return response()->json(['message' => 'Course Code not found this staff'], 404);
                }

                $course_student = CourseStudent::where('student_id', $student->id)
                                                ->where('course_id', $course->id)
                                                ->first();
                $course_student->ca_mark = $mark['ca_mark'];
                $course_student->exam_mark = $mark['exam_mark'];
                $course_student->grade = strtoupper($mark['grade']);
                $course_student->save();
            }
            DB::commit();
            return response()->json(['message' => 'Successfully registered marks'], 200);
        } catch (\Exception $e) {
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
        }
    }

    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
        ]);

        DB::beginTransaction();
        try {
            $staff = $request->user();
            $staff->email = $request['email'];
            $staff->password = Hash::make($request['password']);
            $staff->phone = $request['phone'];
            $staff->marital_status = $request['marital_status'];
            $staff->save();

            DB::commit();
            //return successful response
            return response()->json(['staff' => $staff, 'message' => 'Staff updated successfully'], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'Staff update Failed!', 'logs' => $e], 409);
        }
    }
}

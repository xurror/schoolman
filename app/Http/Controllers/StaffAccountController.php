<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

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

        foreach($courses as $course) {
            error_log($course->id);
            $course_students = CourseStudent::where('course_id', $course->id)->get();
            foreach($course_students as $course_student) {
                error_log($course_student->id);
                $course_details = (object) [
                    'matricule' => User::where(
                                    'id', Student::where('id', $course_student->student_id)->first()->user_id
                                )->first()->matricule,
                    'code' => $course->code,
                    'ca_mark' => $course_student->ca_mark,
                    'exam_mark' => $course_student->exam_mark,
                    'grade' => $course_student->grade,
                ];
                array_push($my_courses, $course_details);
            }
        }
        return $my_courses;

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



        foreach ($request['marks'] as $mark) {
            $students = User::join('students', 'users.id', '=', 'students.user_id')
                            ->where('matricule', $mark['matricule'])->get();
            $courses = Course::where('code', $mark['code'])->get();

            foreach ($students as $student) {
                error_log($student->id);
                error_log($student->user_id);
                foreach ($courses as $course) {
                    if ($student->matricule == $mark['matricule']) {
                        error_log($course->id);
                        $course_student = CourseStudent::where([
                                                            ['student_id', $student->user_id],
                                                            ['course_id', $course->id]
                                                        ])->first();
                        if ($course_student == null)
                            break;
                        $course_student->ca_mark = $mark['ca_mark'];
                        $course_student->exam_mark = $mark['exam_mark'];
                        $course_student->grade = $mark['grade'];
                        $course_student->save();
                    }
                }
            }
        }

        foreach($students as $student) {

        }
        return response()->json(['message' => 'Successfully registered marks'], 200);
    }
}

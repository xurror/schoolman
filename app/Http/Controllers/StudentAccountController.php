<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Department;
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
    public function getAllCourses()
    {
        try {
            $departments = Department::select('id', 'faculty_id', 'name')->get();
            $departments_courses = array();
            foreach($departments as $department) {
                $courses = Course::select('id', 'department_id', 'staff_id', 'code', 'title', 'credits')
                                ->where('department_id', $department->id)->get();
                $department['courses'] = $courses;
                array_push($departments_courses, $department);
            }
            return $departments_courses;
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An Error Occurred!', 'logs' => $e], 409);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getRegisteredCourses()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            $courses = $student->courses;
            return $courses;
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function registerCourses(Request $request)
    {
        DB::beginTransaction();
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            $this->validate($request, [
                'codes' => 'required',
            ]);

            $courses_id = array();

            foreach($request['codes'] as $code) {
                $course = Course::where('code', strtoupper($code['code']))->first();
                if ($course == NULL) {
                    return response()->json(['message' => 'Course not found'], 404);
                } else {
                    $course_id = $course->id;
                    array_push($courses_id, $course_id);
                }
            }

            $student->courses()->sync($courses_id);
            DB::commit();
            return response()->json(['message' => 'Courses successfully registered'], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getFees()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            $fees = $student->fees;
            return $fees;
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getResults()
    {
        try {
            $user = Auth::user();
            $student = Student::where('user_id', $user->id)->first();
            $courses = $student->courses;
            $results = array();

            foreach($courses as $course) {
                $course_student = DB::table('course_student')
                                    ->where('student_id', $student->id)
                                    ->where('course_id', $course->id)
                                    ->first();
                $result = (object) [
                    'course_id' => $course->id,
                    'code' => strtoupper($course->code),
                    'ca_mark' => $course_student->ca_mark,
                    'exam_mark' => $course_student->exam_mark,
                    'grade' => $course_student->grade,
                ];
                array_push($results, $result);
            }
            return response()->json(['Results' => $results, 'message' => 'Student results'], 200);
        } catch (\Exception $e) {
            //return error message
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
        ]);

        DB::beginTransaction();
        try {
            $user = $request->user();
            $user->email = $request['email'];
            $user->password = Hash::make($request['password']);
            $user->phone = $request['phone'];
            $user->marital_status = $request['marital_status'];
            $user->save();

            DB::commit();
            //return successful response
            return response()->json(['user' => $user, 'message' => 'User updated'], 200);
        } catch (\Exception $e) {
            //return error message
            DB::rollback();
            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'User update Failed!', 'logs' => $e], 409);
        }
    }
}

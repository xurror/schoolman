<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\Faculty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function all()
    {
        $courses_list = Course::join('departments', 'courses.department_id', '=', 'departments.id')
                        ->join('staff', 'courses.staff_id', '=', 'staff.id')
                        ->select('courses.id', 'courses.code', 'courses.title', 'courses.credits', 'staff.user_id as matricule', 'departments.name as department_name')
                        ->get();
        $courses = array();
        foreach ($courses_list as $course) {
            $course->matricule = User::where('id', $course->matricule)->first()->matricule;
            array_push($courses, $course);
        }
        return response()->json(['Courses' => $courses], 200);
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
            'department_id' => 'required|integer',
            'staff_id' => 'required|integer',
            'code' => 'required|string|unique:courses,code',
            'title' => 'required|string',
            'credits' => 'required|integer',
        ]);

        try {
            $course = new Course();
            $course->department_id = $request['department_id'];
            $course->staff_id = $request['staff_id'];
            $course->code = $request['code'];
            $course->title = $request['title'];
            $course->credits = $request['credits'];
            $course->save();

            return response()->json(['Course' => $course, 'message' => 'Department Created'], 200);
        } catch (\Exception $e) {

            error_log('An error occurred caused by ' . $e);
            return response()->json(['message' => 'An error occurred!', 'logs' => $e], 409);
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
            $course = Course::join('departments', 'courses.department_id', '=', 'departments.id')
                            ->join('staff', 'courses.staff_id', '=', 'staff.id')
                            ->select('courses.id', 'courses.code', 'courses.title', 'courses.credits', 'staff.user_id as matricule', 'departments.name as department_name')
                            ->where('courses.id', $id)
                            ->first();

            if ($course == null) {
                return response()->json(['message' => 'Resource not found'], 404);
            } else {
                $course->matricule = User::where('id', $course->matricule)->first()->matricule;
                return response()->json(['Course' => $course], 200);
            }
        } catch(\Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
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
        try {
            $course = Course::findOrFail($id);
            $this->validate($request, [
                'department_id' => 'required|integer',
                'staff_id' => 'required|integer',
                'code' => 'required|string|unique:courses,code',
                'title' => 'required|string',
                'credits' => 'required|integer',
            ]);

            $course->department_id = $request['department_id'];
            $course->staff_id = $request['staff_id'];
            $course->code = $request['code'];
            $course->title = $request['title'];
            $course->credits = $request['credits'];
            $course->save();
            return response()->json(['Course' => $course, 'message' => 'Department Updated'], 200);
        } catch(\Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
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
            Course::findOrFail($id)->delete();
        } catch(\Exception $e) {
            error_log('An error occurred ' . $e);
            return response()->json(['message' => 'An error Occurred', 'logs' => $e], 500);
        }
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Course;
use App\Models\CourseStudent;
use App\Models\Staff;
use App\Models\Student;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->has('role')) {
            if ($request['role'] == 'staff') {
                if ($request['filter'] == 'nature_of_job') {
                    $teaching_staff = Staff::join('users', 'users.id', '=', 'staff.user_id')
                                            ->select(
                                                'staff.id', 'staff.department_id', 'staff.nature_of_job', 'staff.basic_pay',
                                                'users.matricule', 'users.name', 'users.email', 'users.phone',
                                                'users.dob', 'users.gender', 'users.marital_status', 'staff.doa'
                                            )->where('staff.nature_of_job', 'teaching')->get();

                    $non_teaching_staff = Staff::join('users', 'users.id', '=', 'staff.user_id')
                                                ->select(
                                                    'staff.id', 'staff.department_id', 'staff.nature_of_job', 'staff.basic_pay',
                                                    'users.matricule', 'users.name', 'users.email', 'users.phone',
                                                    'users.dob', 'users.gender', 'users.marital_status'
                                                )->where('staff.nature_of_job', 'non-teaching')->get();

                    return response()->json(['teaching_staff' => $teaching_staff, 'non_teaching_staff' => $non_teaching_staff], 200);

                } else if ($request['filter'] == 'datewise') {
                    $begin = new \DateTime('2020-01-01');
                    $end = date('Y-m-d');
                    $daterange = new \DatePeriod($begin, new \DateInterval('P1D'), $end);

                    $staff_per_date = array();

                    foreach($daterange as $date){
                        $staff = Staff::join('users', 'users.id', '=', 'staff.user_id')
                                        ->select(
                                            'staff.id', 'staff.department_id', 'staff.nature_of_job', 'staff.basic_pay',
                                            'users.matricule', 'users.name', 'users.email', 'users.phone',
                                            'users.dob', 'users.gender', 'users.marital_status', 'staff.doa'
                                        )->where('staff.doa', $date)->get();
                        if (!$staff->isEmpty()) {
                            array_push($staff_per_date, [$date->format('Y-m-d') => $staff]);
                        } else {
                            continue;
                        }
                    }
                    return response()->json(['staff_per_date' => $staff_per_date], 200);

                } else {
                    return response()->json(['message' => 'staff category not found'], 404);
                }


            } else if ($request['role'] == 'student') {
                if ($request['filter'] == 'classwise') {
                    $class = array();
                    $courses = Course::select('id', 'code')->get();

                    foreach($courses as $course) {
                        $course_students = CourseStudent::where('course_id', $course->id)->get();
                        $students = array();

                        foreach($course_students as $course_student) {
                            $student = Student::join('users', 'users.id', '=', 'students.user_id')
                                                ->select(
                                                    'students.id', 'students.department_id', 'students.doa',
                                                    'users.matricule', 'users.name', 'users.email', 'users.phone',
                                                    'users.dob', 'users.gender', 'users.marital_status'
                                                )->where('students.id', $course_student->student_id)->get();
                            array_push($students, $student);
                        }
                        array_push($class, [$course->code => $students]);

                    }

                    return response()->json(['classes' => $class], 200);

                } else if ($request['filter'] == 'datewise') {
                    $begin = new \DateTime('2020-01-01');
                    $end = date('Y-m-d');
                    $daterange = new \DatePeriod($begin, new \DateInterval('P1D'), $end);

                    $students_per_date = array();

                    foreach($daterange as $date){
                        $students = Student::join('users', 'users.id', '=', 'students.user_id')
                                        ->select(
                                            'students.id', 'students.department_id', 'students.doa',
                                            'users.matricule', 'users.name', 'users.email', 'users.phone',
                                            'users.dob', 'users.gender', 'users.marital_status'
                                        )->where('students.doa', $date)->get();

                        if (!$students->isEmpty()) {
                            array_push($students_per_date, [$date->format('Y-m-d') => $students]);
                        } else {
                            continue;
                        }

                    }

                    return response()->json(['students_per_date' => (object) $students_per_date], 200);

                } else if ($request['filter'] == 'namewise') {
                    $students = Student::join('users', 'users.id', '=', 'students.user_id')
                                        ->select(
                                            'students.id', 'students.department_id', 'students.doa',
                                            'users.matricule', 'users.name', 'users.email', 'users.phone',
                                            'users.dob', 'users.gender', 'users.marital_status'
                                        )->orderBy('users.name')->get();
                    return response()->json(['students' => $students], 200);
                }
            } else {
                return response()->json(['message' => 'Unknown filter'], 404);
            }

        } else {
            return response()->json(['message' => 'Unknown role'], 404);
        }
    }
}

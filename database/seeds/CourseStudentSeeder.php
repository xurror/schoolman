<?php

use App\Models\CourseStudent;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;

class CourseStudentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $courses_students = CourseStudent::all();
        foreach($courses_students as $course_student) {
            $course_student->ca_mark = rand(0, 30);
            $course_student->exam_mark = rand(0, 70);
            $course_student->grade = strtoupper(Str::random(1));
            $course_student->save();
        }
    }
}

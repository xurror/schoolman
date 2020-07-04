<?php

use App\Models\Course;
use App\Models\Student;
use Illuminate\Database\Seeder;

class CourseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $student = Student::find(1);

        $course1 = Course::create([
            'department_id' => 1,
            'staff_id' => 1,
            'code' => 'CEF438',
            'title' => 'Databases and Administration',
            'credits' => 5,
        ]);

        $course2 = Course::create([
            'department_id' => 1,
            'staff_id' => 1,
            'code' => 'CEF426',
            'title' => 'Software Engineering and Design',
            'credits' => 5,
        ]);

        $course3 = Course::create([
            'department_id' => 1,
            'staff_id' => 1,
            'code' => 'CEF444',
            'title' => 'AI and Machine Learning',
            'credits' => 5,
        ]);

        $course4 = Course::create([
            'department_id' => 1,
            'staff_id' => 1,
            'code' => 'CEF462',
            'title' => 'Digital Image Processing',
            'credits' => 5,
        ]);

        $course5 = Course::create([
            'department_id' => 1,
            'staff_id' => 1,
            'code' => 'CEF440',
            'title' => 'Internet and Mobile Programming',
            'credits' => 5,
        ]);

        $courses = [$course1->id, $course2->id, $course3->id, $course4->id, $course5->id];
        $student->courses()->attach($courses);

    }
}

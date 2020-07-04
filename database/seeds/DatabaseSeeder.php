<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call('FacultySeeder');
        $this->call('DepartmentSeeder');
        $this->call('UserSeeder');
        $this->call('CourseSeeder');
        $this->call('FeeSeeder');
        $this->call('CourseStudentSeeder');
    }
}

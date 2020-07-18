<?php

use App\Models\Staff;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $faker = Faker\Factory::create();

        User::create([
            'matricule' => strtoupper('admin123'),
            'name' => 'admin',
            'email' => 'admin@gmail.com',
            'password' => Hash::make('password'),
            'phone' => '123456789',
            'dob' => '2020-06-02',
            'gender' => 'male',
            'marital_status' => 'single',
            'role' => 'admin',
        ]);

        $student = User::create([
            // 'matricule' => strtoupper(Str::random(8)),
            'matricule' => strtoupper('student123'),
            'name' => $faker->name,
            'email' => $faker->unique()->email,
            // 'password' => Hash::make($faker->password(8)),
            'password' => Hash::make("password"),
            'phone' => $faker->unique()->e164PhoneNumber,
            'dob' => $faker->dateTimeThisCentury->format('Y-m-d'),
            'gender' => 'male',
            'marital_status' => 'single',
            'role' => 'student',
        ]);
        $student_details = new Student(['department_id' => 1, 'doa' => date('Y-m-d')]);
        $student->student()->save($student_details);

        $staff = User::create([
            // 'matricule' => strtoupper(Str::random(8)),
            'matricule' => strtoupper('staff123'),
            'name' => $faker->name,
            'email' => $faker->unique()->email,
            // 'password' => Hash::make($faker->password(8)),
            'password' => Hash::make("password"),
            'phone' => $faker->unique()->e164PhoneNumber,
            'dob' => $faker->dateTimeThisCentury->format('Y-m-d'),
            'gender' => 'female',
            'marital_status' => 'single',
            'role' => 'staff',
        ]);
        $staff->save();
        $staff_details = new Staff([
            'department_id' => 1,
            'nature_of_job' => 'teaching',
            'basic_pay' => '100000',
            'doa' => date('Y-m-d')
        ]);
        $staff->student()->save($staff_details);
    }
}

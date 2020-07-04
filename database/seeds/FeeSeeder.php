<?php

use App\Models\Fee;
use Illuminate\Database\Seeder;

class FeeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Fee::create(['student_id' => 1, 'fine' => 25000,]);
        Fee::create(['student_id' => 1, 'fine' => 15000,]);
        Fee::create(['student_id' => 1, 'fine' => 10000,]);
    }
}

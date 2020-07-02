<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('matricule')->unique()->nullable(false);
            $table->string('name')->nullable(false);

            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->rememberToken();

            $table->string('phone')->nullable(false);
            $table->date('dob')->nullable(false)->comment('Date of birth');

            $table->enum('gender', ['male', 'female', 'other'])->nullable(false);
            $table->enum('marital_status', ['married', 'single'])->nullable(false);
            $table->enum('role', ['student', 'staff'])->nullable(false);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}

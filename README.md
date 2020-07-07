# Lumen PHP Framework

[![Build Status](https://travis-ci.org/laravel/lumen-framework.svg)](https://travis-ci.org/laravel/lumen-framework)
[![Total Downloads](https://poser.pugx.org/laravel/lumen-framework/d/total.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![Latest Stable Version](https://poser.pugx.org/laravel/lumen-framework/v/stable.svg)](https://packagist.org/packages/laravel/lumen-framework)
[![License](https://poser.pugx.org/laravel/lumen-framework/license.svg)](https://packagist.org/packages/laravel/lumen-framework)

Laravel Lumen is a stunningly fast PHP micro-framework for building web applications with expressive, elegant syntax. We believe development must be an enjoyable, creative experience to be truly fulfilling. Lumen attempts to take the pain out of development by easing common tasks used in the majority of web projects, such as routing, database abstraction, queueing, and caching.

## Official Documentation

Documentation for the framework can be found on the [Lumen website](https://lumen.laravel.com/docs).

## Contributing

Thank you for considering contributing to Lumen! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Security Vulnerabilities

If you discover a security vulnerability within Lumen, please send an e-mail to Taylor Otwell at taylor@laravel.com. All security vulnerabilities will be promptly addressed.

## License

The Lumen framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).


## Docs

# How to run

`php artisan`

# Authentication endpoints and expected input:

Authentication uses jwt. Use default logins below for admin, student and staff

# 1. Login

    - ADMIN:
    `POST /api/auth/login contentType: application/json body: {
            "username":"admin123",
            "password":"password",
            "role":"admin"
        }`
    
    - STUDENT:
    `POST /api/auth/login contentType: application/json body: {
            "username":"student123",
            "password":"password",
            "role":"student"
        }`

    - STAFF
    `POST /api/auth/login contentType: application/json body: {
            "username":"staff123",
            "password":"password",
            "role":"staff"
        }`

    - returns [user, token, token_type, expires_in]

# 2. Admin Actions from Admin Account

    # Update Profile

        - `PUT /account/student contentType: application/json body: {
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'marital_status' => 'required',
            }`
        - return user

    2. Get Courses
        - `GET /account/student/courses

        -return all registered courses

    3. Register Courses
        - `POST /account/student/courses contentType: application/json body: {
                'codes' => [
                    {
                        "code":"required eg CEFXXX"
                    },
                ]
            }`

        - return registered Courses

    2. Get Results
        - `GET /account/student/results

        -return all courses results

    2. Get fees
        - `GET /account/student/fees

        -return all fees

# 3. Student Actions from Student Account

    # Update Profile

        - `PUT /account/staff contentType: application/json body: {
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'marital_status' => 'required',
            }`
        - return user

    2. Get Courses
        - `GET /account/student/courses

        -return all registered courses

    3. Register Marks
        - `POST /account/student/marks contentType: application/json body: {
                'marks' => [
                    {
                        "matricule":"required",
                        "code":"required eg CEFXXX",
                        "ca_mark":"required",
                        "exam_mark":"required",
                        "grade":"required",
                    },
                ]
            }`

# 4. Staff Actions from Staff Account

    # Account Management

    1. Update profile

        - `PUT /admin contentType: application/json body: {
                'matricule' => 'required|string|min:5|max:15',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'dob' => 'required|date',
                'marital_status' => 'required', 
            }`

        - return $user;
    

    # Student Management

    2. Get all students

        - `GET /admin/student`

    4. Create a single student

        - `POST /admin/student contentType: application/json body: {
                'matricule' => 'required|string|min:5|max:15|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'dob' => 'required|date',
                'gender' => 'required',
                'marital_status' => 'required',
                'department' => 'required',
            }`

        - return created student

    6. Get single student

        - `GET /admin/student/{id}`

    8. Update a single student

        - `PUT /admin/student/{id} contentType: application/json body: {
                'matricule' => 'required|string|min:5|max:15|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'dob' => 'required|date',
                'gender' => 'required',
                'marital_status' => 'required',
                'department' => 'required',
            }`

        - return created student

    10. delete single students

        - `DELETE /admin/student/{id}`



    # Staff 
    
    3. Get all staff

        - `GET /admin/staff`

    5. Create a single staff member

        - `POST /admin/staff contentType: application/json body: {
                'matricule' => 'required|string|min:5|max:15|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'dob' => 'required|date',
                'gender' => 'required',
                'marital_status' => 'required',
                'department' => 'required',
                'nature_of_job' => 'required|string|max:255',
                'basic_pay' => 'required|string|max:255',
            }`

        - return created staff member

    7. Get single staff member

        - `GET /admin/staff/{id}`

    9. Update a single staff member

        - `PUT /admin/staff/{id} contentType: application/json body: {
                'matricule' => 'required|string|min:5|max:15|unique:users',
                'name' => 'required|string|max:255',
                'email' => 'required|string|email|max:255|unique:users',
                'password' => 'required|string|min:8',
                'phone' => 'required|string|min:9|max:12',
                'dob' => 'required|date',
                'gender' => 'required',
                'marital_status' => 'required',
                'department' => 'required',
                'nature_of_job' => 'required|string|max:255',
                'basic_pay' => 'required|string|max:255',
            }`

        - return created staff member

    11. delete single staff member

        - `DELETE /admin/staff/{id}`



    # Departments Management

    2. Get all departments

        - `GET /admin/departments`

    4. Create a single department

        - `POST /admin/department contentType: application/json body: {
                'name' => 'required|string|min:5',
                'faculty' => 'required',
            }`

        - return created department

    6. Get single department

        - `GET /admin/department/{id}`

    8. Update a single department

        - `PUT /admin/department/{id} contentType: application/json body: {
                'name' => 'required|string|min:5',
                'faculty' => 'required',
            }`

        - return created department

    10. delete single department

        - `DELETE /admin/department/{id}`



    # Faculty Management

    3. Get all faculties

        - `GET /admin/faculty`

    5. Create a single faculty

        - `POST /admin/faculty contentType: application/json body: {
                'name' => 'required|string|min:5',
            }`

        - return created faculty

    7. Get single faculty

        - `GET /admin/faculty/{id}`

    9. Update a single faculty

        - `PUT /admin/faculty/{id} contentType: application/json body: {
                'name' => 'required|string|min:5',
            }`

        - return created faculty

    11. delete single faculty

        - `DELETE /admin/faculty/{id}`



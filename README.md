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

# Authentication endpoints and expected input:

Authentication uses jwt. Use default logins below for admin, student and staff

1. Login
    - ADMIN:
    `POST /auth/login contentType: application/json body: {               "username":"admin123", "password":"password", "role":"admin" }`
    
    - STUDENT:
    `POST /auth/login contentType: application/json body: {               "username":"student123", "password":"password", "role":"admin" }`

    - STAFF
    `POST /auth/login contentType: application/json body: {               "username":"staff123", "password":"password", "role":"admin" }`

    - returns [user, token, token_type, expires_in]

2. Admin Actions from Admin Account
    - Update profile
    `PUT /admin contentType: application/json body: {
            'matricule' => 'required|string|min:5|max:15',
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'phone' => 'required|string|min:9|max:12',
            'dob' => 'required|date',
            'marital_status' => 'required', 
        }`

    return $user;
    
    - Get all students
    `GET /admin/student`

    - Get all staff
    `GET /admin/staff`

    - Create a single student
    `POST /admin/student contentType: application/json body: {
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
    return created student

    - Create a single staff member
    `POST /admin/staff contentType: application/json body: {
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
    return created staff member

    - Get single students
    `GET /admin/student/{id}`

    - Get single staff member
    `GET /admin/staff/{id}`

    - Update a single student
    `PUT /admin/student/{id} contentType: application/json body: {
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
    return created student

    - Update a single staff member
    `PUT /admin/staff/{id} contentType: application/json body: {
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
    return created staff member

    - delete single students
    `DELETE /admin/student/{id}`

    - delete single staff member
    `DELETE /admin/staff/{id}`


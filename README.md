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

Authentication uses jwt.

1. Registration:
    - ```POST /auth/register contentType: application/json body: {
            "matricule":"required|string|min:5|max:15",
            "name":"required|string|max:255",
            "email":"required|string|email|max:255|unique:users",
            "password":"required|string|min:8|confirmed",
            "phone":"required|string|min:9|max:12",
            "dob":"required|date eg: 2020-02-03",
            "gender":"enum[male, female, other]",
            "marital_status":"enum[single, married]",
            "role":"enum[student, staff]"
        }```
    - returns [token, token_type, expires_in]

1. Login
    - ```POST /auth/login contentType: application/json body: {
            "username":"FE17A091",
            "password":"password"
        }```
    - returns user

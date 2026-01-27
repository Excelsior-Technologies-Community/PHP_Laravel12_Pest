# PHP_Laravel12_Pest

##  Project Introduction

Testing is a critical part of modern backend development to ensure application stability, correctness, and long-term maintainability.
This project demonstrates how to implement **automated testing in Laravel 12 using Pest PHP**, a modern testing framework built on top of PHPUnit.

The goal of this project is to help developers understand:

* How Pest works with Laravel 12
* How to write clean and readable tests
* How to test HTTP routes, controllers, and database interactions
* How Laravel uses an isolated test database during testing

This project follows **real-world Laravel testing practices** used in professional environments.

---

##  Project Overview

**PHP_Laravel12_Pest** is a sample Laravel 12 application created to demonstrate **Pest PHP integration and testing best practices**.

### What this project includes

* Laravel 12 application setup
* Pest PHP (v3.8) compatible with PHP 8.2
* Feature testing using real HTTP requests
* Database testing using Laravel model factories
* Automatic database refresh for every test
* Clean and standard Laravel project structure

### Testing Scenarios Covered

* **Unit Testing**
  Tests core PHP logic without touching the database

* **Feature Testing**
  Tests complete request–response flow including:

  * Route
  * Controller
  * Database

### Key Highlights

* Uses Laravel’s **default `users` migration and factory**
* No permanent data stored during tests
* Database resets automatically after each test
* Clean, readable test syntax using Pest

---

##  Prerequisites

Make sure you have the following installed:

- PHP >= 8.2
- Composer
- MySQL
- Laravel Installer (optional)
- Node & NPM (optional)

---

##  Step 1: Create Laravel 12 Project

Run the following command:

```bash
composer create-project laravel/laravel PHP_Laravel12_Pest "12.*"
```

---

## Step 2: Open Project Folder

```bash
cd PHP_Laravel12_Pest
```

---

## Step 3: Install Pest (Compatible Version for PHP 8.2)

Laravel 12 uses PHPUnit 11 and PHP 8.2, so Pest version 3.8.0 is compatible.

Run:

```bash
composer require pestphp/pest:^3.8 --dev --with-all-dependencies
```

---

## Step 4: Configure Database

Open .env file and configure DB:

```.env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_pest
DB_USERNAME=root
DB_PASSWORD=
```

Run migration:

```bash
php artisan migrate
```

---


## Step 5: Manual Pest Setup (Laravel 12)

Since Laravel 12 does not provide pest:install, we will create Pest files manually.

### 5.1 Create tests/Pest.php

```php
<?php

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

uses(TestCase::class, RefreshDatabase::class)->in('Feature');
```


### 5.2 Create tests/Feature/UserTest.php

```php
<?php

use App\Models\User;

test('users route returns json', function () {
    // Arrange: create 3 users in test database
    User::factory()->count(3)->create();

    // Act: call the route
    $response = $this->get('/users');

    // Assert: response checks
    $response->assertStatus(200)
             ->assertJson([
                 'status' => true,
                 'message' => 'User List',
             ])
             ->assertJsonCount(3, 'data'); 
});
```

### 5.3 Create tests/Unit/MathTest.php

```php
<?php

test('addition works', function () {
    $sum = 2 + 3;
    expect($sum)->toBe(5);
});
```

---

## Step 6: Create User Controller 

Create Controller

```bash
php artisan make:controller UserController
```

File: app/Http/Controllers/UserController.php

```php
<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        return response()->json([
            'status' => true,
            'message' => 'User List',
            'data' => User::all()
        ]);
    }
}
```

---

## Step 7: Web routes

File: routes/web.php

```php
use App\Http\Controllers\UserController;

Route::get('/users', [UserController::class, 'index']);
```

---

## Step 8: Run Pest Tests

Run using Pest binary

```bash	
./vendor/bin/pest
```

Or run using Laravel test command

```bash
php artisan test
```

---

## Step 9: Pest Output Example

```pgsql
PASS  Tests\Unit\MathTest
✓ addition works

PASS  Tests\Feature\UserTest
✓ users route returns json
```

---

##  Project Structure

```
PHP_Laravel12_Pest/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── UserController.php
│   ├── Models/
│   │   └── User.php
│
├── database/
│   ├── migrations/
│   │   ├── create_users_table.php
│   │   ├── create_password_reset_tokens_table.php
│   │   └── create_sessions_table.php
│   └── seeders/
│
├── routes/
│   └── web.php
│
├── tests/
│   ├── Feature/
│   │   └── UserTest.php
│   ├── Unit/
│   │   └── MathTest.php
│   └── Pest.php
│
├── phpunit.xml
├── composer.json
└── README.md
```
---

## Output

**Run Test**

<img width="1919" height="252" alt="Screenshot 2026-01-27 110903" src="https://github.com/user-attachments/assets/153b66eb-812b-47f2-81be-315da85a7376" />

---

Your PHP_Laravel12_Pest Project is Now Ready!

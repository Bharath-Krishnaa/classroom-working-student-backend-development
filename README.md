# Coding Challenge: PHP User & Role Management Demo

This project demonstrates core PHP concepts through a simple user and role management system.

## Features Demonstrated

- Abstract classes and inheritance
- Interfaces and traits
- Public / protected / private visibility
- Constructors and magic methods (__construct, __toString, __get, __set)
- Constants and static properties
- Exception handling (email validation)
- Numeric and associative arrays
- Array functions (array_map, array_filter)
- Closures (anonymous functions)
- Control structures (if, switch, foreach, while)
- Namespaces and Composer PSR-4 autoloading
- Optional superglobal usage ($_GET)
- Basic demo testing using assert()

## Project Structure

src/
   UserBase.php
   AdminUser.php
   CustomerUser.php
   Traits/
         CanLogin.php
   Interfaces/
             Resettable.php
tests/
     demo.php
composer.json
README.md
.gitignore


## Requirements

- PHP 8+
- Composer

## How to Run

1. Install dependencies:

```bash
composer install
2. Run the demo script:
php tests/demo.php

## Output
The demo prints example users, array operations, login status, exception handling, and basic assertions.

This project was completed as part of a backend development exercise to demonstrate broad PHP fundamentals.

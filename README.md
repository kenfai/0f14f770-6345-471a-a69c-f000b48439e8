# Coding Challenge
### Broadband Solutions Technical Assessment
- Date: 2025-05-25
- Author: NG KEN FAI ([@kenfai](https://github.com/kenfai))

## A. Prerequisites

### 1. PHP version
This application has been tested to run in **PHP8.4.3** CLI.

### 2. Setup
There is no need to install any dependencies.

## B. Command to run the application

### 1. `cd` into the project directory where `index.php` is located
```
$ cd <project-directory>
```

### 2. Make sure PHP is installed on your machine
```
$ php -v
PHP 8.4.x (cli)...
```

### 3. Run the application
```
$ php index.php
Please enter the following
...
```

## C. Command to run tests
**PHPUnit** is included in the project as a phar file. To run the tests:

### 1. Make PHPUnit executable
```
$ chmod +x phpunit
```

### 2. Run the tests
```
$ ./phpunit --bootstrap autoload.php tests
```

_- End of Document -_
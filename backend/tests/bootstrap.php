<?php

// Report all PHP errors
error_reporting(E_ALL);

// Display errors on screen
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

// Set error log file
ini_set('error_log', __DIR__ . '/../logs/php_errors.log');

// Configure error handling
set_error_handler(function($errno, $errstr, $errfile, $errline) {
    // Don't handle silenced errors
    if (error_reporting() === 0) {
        return false;
    }

    throw new ErrorException($errstr, 0, $errno, $errfile, $errline);
});

// Configure exception handling
set_exception_handler(function($exception) {
    error_log(sprintf(
        "Uncaught %s: %s\nStack trace:\n%s",
        get_class($exception),
        $exception->getMessage(),
        $exception->getTraceAsString()
    ));

    // Re-throw the exception for PHPUnit to handle
    throw $exception;
});

// Ensure directory for logs exists
if (!is_dir(__DIR__ . '/../logs')) {
    mkdir(__DIR__ . '/../logs', 0777, true);
}

// Load Composer's autoloader
require_once __DIR__ . '/../vendor/autoload.php';

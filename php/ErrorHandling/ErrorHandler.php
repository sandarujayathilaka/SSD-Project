<?php
// Centralized error handling for the application

// Custom error handler
function customErrorHandler($errno, $errstr, $errfile, $errline) {
    // Log error details to logs/error.log
    error_log("Error [$errno]: $errstr in $errfile on line $errline", 3, __DIR__ . "/logs/error.log");
    
    // Display a generic message to the user
    echo "<p>Oops! Something went wrong. Please try again later.</p>";
    return true;
}

// Custom exception handler
function customExceptionHandler($exception) {
    // Log exception details to logs/error.log
    error_log("Exception: " . $exception->getMessage(), 3, __DIR__ . "/logs/error.log");

    // Display a generic message to the user
    echo "<p>An unexpected error occurred. Please contact support.</p>";
}

// Set custom handlers
set_error_handler("customErrorHandler");
set_exception_handler("customExceptionHandler");

// Suppress error display to the user in production
ini_set('display_errors', 0);
ini_set('log_errors', 1);
error_reporting(E_ALL); // Report all errors

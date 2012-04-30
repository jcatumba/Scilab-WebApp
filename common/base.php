<?php
    // Set the error reporting level
    error_reporting(E_ALL);
    ini_set("display_errors", 1);
 
    // Start a PHP session
    session_start();
 
    // Include site constants
    include_once "inc/constants.inc.php";
 
    // Create a database object
    try {
        $db = new PDO("pgsql:host=".DB_HOST.";dbname=".DB_NAME.";user=".DB_USER.";password=".DB_PASS);
    } catch (PDOException $e) {
        echo 'Connection failed: ' . $e->getMessage();
        exit;
    }
?>

<?php

$host     = 'localhost:3306';
$dbname   = 'vocabill-erp';
$username = 'root';
$password = '';

try {
    $db = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // Report errors as exceptions
    $db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC); // Fetch data as associative arrays
} catch (PDOException $e) {
    die("Database connection failed: " . $e->getMessage());
}

?>

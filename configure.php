<?php

$DB_HOST = 'localhost';
$DB_USER = 'mmusyoka2';
$DB_PASS = 'mmusyoka2';        
$DB_NAME = 'mmusyoka2';


$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);


if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
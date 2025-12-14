<?php

$DB_HOST = 'localhost';
$DB_USER = 'mmusyoka2';
$DB_PASS = 'mmusyoka2';        
$DB_NAME = 'mmusyoka2';


$link = mysqli_connect($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);


if (!$link) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
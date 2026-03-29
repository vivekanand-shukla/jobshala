<?php
// database connection file
// this file connects to mysql database

$host = "localhost";
$username = "root";
$password = "";
$database = "jobshala";

// connect to database using mysqli
$conn = mysqli_connect($host, $username, $password, $database);

// check if connection failed
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

<?php

$servername = "localhost";
$username = "root";
$password = ""; 
$dbname = "comq_booking_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

echo "Connected successfully ";
?>
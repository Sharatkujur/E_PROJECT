<?php
$con = mysqli_connect("localhost","root", "", "amwaydb");
if (!$con) {
    die("Database connection failed: " . mysqli_connect_error());
}
?>
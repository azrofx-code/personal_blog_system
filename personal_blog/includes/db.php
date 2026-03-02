<?php

$connection = mysqli_connect("localhost", "root", "", "personal_blog");

if (!$connection) {
    exit("Database connection failed.");
}

mysqli_set_charset($connection, "utf8mb4");

?>
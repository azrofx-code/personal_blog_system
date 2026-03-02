<?php

require_once "db.php";

function confirmQuery($result) {
    global $connection;
    if(!$result) {
        die("QUERY FAILED: " . mysqli_error($connection));
    }
}

function escape($string) {
    global $connection;
    return mysqli_real_escape_string($connection, trim($string));
}

function fetchCategories() {
    global $connection;
    $query = "SELECT * FROM categories";
    $select_categories = mysqli_query($connection, $query);
    confirmQuery($select_categories);
    return $select_categories;
}
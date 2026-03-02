<?php

if (!function_exists('confirmQuery')) {
    function confirmQuery($result) {
        global $connection;
        if (!$result) {
            die("QUERY FAILED: " . mysqli_error($connection));
        }
    }
}

function isAdmin() {
    return isset($_SESSION['user_role']) && $_SESSION['user_role'] === 'admin';
}

function requireAdmin() {
    if (!isAdmin()) {
        header("Location: ../index.php");
        exit();
    }
}
?>
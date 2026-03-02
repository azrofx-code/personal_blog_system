<?php 
include "includes/admin_header.php"; 
include "includes/admin_navigation.php"; 
requireAdmin();

if (isset($_GET['change_to_admin']) && is_numeric($_GET['change_to_admin'])) {
    $user_id = (int) $_GET['change_to_admin'];
    $stmt = mysqli_prepare($connection, "UPDATE users SET user_role = 'admin' WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    header("Location: users.php");
    exit();
}

if (isset($_GET['change_to_sub']) && is_numeric($_GET['change_to_sub'])) {
    $user_id = (int) $_GET['change_to_sub'];
    $stmt = mysqli_prepare($connection, "UPDATE users SET user_role = 'subscriber' WHERE user_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $user_id);
    mysqli_stmt_execute($stmt);
    header("Location: users.php");
    exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $user_id = (int) $_GET['delete'];

    if ($user_id !== $_SESSION['user_id']) {
        $stmt = mysqli_prepare($connection, "DELETE FROM users WHERE user_id = ?");
        mysqli_stmt_bind_param($stmt, "i", $user_id);
        mysqli_stmt_execute($stmt);
    }

    header("Location: users.php");
    exit();
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">User Management</h2>

    <table class="table table-bordered table-hover">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Username</th>
                <th>Firstname</th>
                <th>Lastname</th>
                <th>Email</th>
                <th>Role</th>
                <th>Make Admin</th>
                <th>Make Subscriber</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>

            <?php
            $result = mysqli_query($connection, "SELECT user_id, username, user_firstname, user_lastname, user_email, user_role FROM users ORDER BY user_id DESC");

            while ($row = mysqli_fetch_assoc($result)) {

                $user_id = (int)$row['user_id'];
                $username = htmlspecialchars($row['username']);
                $user_firstname = htmlspecialchars($row['user_firstname']);
                $user_lastname = htmlspecialchars($row['user_lastname']);
                $user_email = htmlspecialchars($row['user_email']);
                $user_role = htmlspecialchars($row['user_role']);

                echo "<tr>";
                echo "<td>{$user_id}</td>";
                echo "<td>{$username}</td>";
                echo "<td>{$user_firstname}</td>";
                echo "<td>{$user_lastname}</td>";
                echo "<td>{$user_email}</td>";
                echo "<td>{$user_role}</td>";
                echo "<td><a class='btn btn-sm btn-warning' href='users.php?change_to_admin={$user_id}'>Admin</a></td>";
                echo "<td><a class='btn btn-sm btn-info' href='users.php?change_to_sub={$user_id}'>Subscriber</a></td>";
                echo "<td><a class='btn btn-sm btn-danger' onclick='return confirm(\"Delete this user?\")' href='users.php?delete={$user_id}'>Delete</a></td>";
                echo "</tr>";
            }
            ?>

        </tbody>
    </table>
</div>

<?php include "includes/admin_footer.php"; ?>
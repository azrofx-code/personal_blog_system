<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
$message = "";

if(isset($_POST['login'])) {

    $username = mysqli_real_escape_string($connection, $_POST['username']);
    $password = mysqli_real_escape_string($connection, $_POST['password']);

    $query = "SELECT * FROM users WHERE username = '{$username}' LIMIT 1";
    $select_user_query = mysqli_query($connection, $query);

    if(!$select_user_query) {
        die("Query Failed" . mysqli_error($connection));
    }

    if(mysqli_num_rows($select_user_query) > 0) {

        $row = mysqli_fetch_assoc($select_user_query);

        $db_user_id = $row['user_id'];
        $db_username = $row['username'];
        $db_user_password = $row['user_password'];
        $db_user_role = $row['user_role'];

        // Logic: Check if it's a hashed password OR a legacy plain text password
        if (password_verify($password, $db_user_password) || $password === $db_user_password) {

            $_SESSION['user_id'] = $db_user_id;
            $_SESSION['username'] = $db_username;
            $_SESSION['user_role'] = $db_user_role;

            if($db_user_role === 'admin') {
                header("Location: admin/index.php");
            } else {
                header("Location: admin/profile.php");
            }
            exit();

        } else {
            $message = "<div class='alert alert-danger'>Invalid credentials.</div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>Invalid credentials.</div>";
    }
}
?>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-4 card p-4 shadow-sm">
            <h2 class="text-center mb-4">Login</h2>

            <?php echo $message; ?>

            <form action="login.php" method="post">
                <div class="mb-3">
                    <input name="username" type="text" class="form-control" placeholder="Username" required>
                </div>
                <div class="mb-3">
                    <input name="password" type="password" class="form-control" placeholder="Password" required>
                </div>
                <button class="btn btn-primary w-100" name="login" type="submit">Login</button>
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
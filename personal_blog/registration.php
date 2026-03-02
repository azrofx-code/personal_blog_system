<?php include "includes/header.php"; ?>
<?php include "includes/navigation.php"; ?>

<?php
$message = "";

if(isset($_POST['submit'])) {
    // Using mysqli_real_escape_string to prevent SQL injection
    $username  = mysqli_real_escape_string($connection, $_POST['username']);
    $firstname = mysqli_real_escape_string($connection, $_POST['firstname']);
    $lastname  = mysqli_real_escape_string($connection, $_POST['lastname']);
    $email     = mysqli_real_escape_string($connection, $_POST['email']);
    $password  = mysqli_real_escape_string($connection, $_POST['password']);

    if(!empty($username) && !empty($firstname) && !empty($lastname) && !empty($email) && !empty($password)) {

        $check_query = "SELECT * FROM users WHERE username = '{$username}' OR user_email = '{$email}'";
        $check_result = mysqli_query($connection, $check_query);

        if(mysqli_num_rows($check_result) > 0) {
            $message = "<div class='alert alert-danger'>Username or Email already exists.</div>";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            $query = "INSERT INTO users (username, user_password, user_firstname, user_lastname, user_email, user_role) ";
            $query .= "VALUES('{$username}','{$hashed_password}','{$firstname}','{$lastname}','{$email}','subscriber')";

            $register_user_query = mysqli_query($connection, $query);
            
            if(!$register_user_query) {
                die("Query Failed" . mysqli_error($connection));
            }

            $message = "<div class='alert alert-success'>Registration successful. <a href='login.php'>Login here</a></div>";
        }
    } else {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 mt-5">
            <div class="card p-4 shadow-sm">
                <h2 class="text-center mb-4">Register</h2>
                <?php echo $message; ?>
                <form action="registration.php" method="post" autocomplete="off">
                    <div class="mb-3">
                        <input type="text" name="username" class="form-control" placeholder="Username" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="firstname" class="form-control" placeholder="First Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="text" name="lastname" class="form-control" placeholder="Last Name" required>
                    </div>
                    <div class="mb-3">
                        <input type="email" name="email" class="form-control" placeholder="Email" required>
                    </div>
                    <div class="mb-3">
                        <input type="password" name="password" class="form-control" placeholder="Password" required>
                    </div>
                    <button class="btn btn-primary w-100" name="submit" type="submit">Register</button>
                </form>
            </div>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
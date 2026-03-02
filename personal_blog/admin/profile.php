<?php 
include "includes/admin_header.php"; 
include "includes/admin_navigation.php"; 

$message = "";

if (isset($_GET['success'])) {
    $message = "<div class='alert alert-success'>Profile updated successfully.</div>";
}

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = (int) $_SESSION['user_id'];

$stmt = mysqli_prepare($connection, "SELECT * FROM users WHERE user_id = ?");
mysqli_stmt_bind_param($stmt, "i", $user_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$user = mysqli_fetch_assoc($result);

if (!$user) {
    header("Location: ../index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $user_firstname = trim($_POST['user_firstname']);
    $user_lastname  = trim($_POST['user_lastname']);
    $username       = trim($_POST['username']);
    $user_email     = trim($_POST['user_email']);
    $user_password  = trim($_POST['user_password']);

    if (!filter_var($user_email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Invalid email address.</div>";
    } else {
        
        // Security Wall 3: Only admins can change roles
        if ($_SESSION['user_role'] === 'admin' && isset($_POST['user_role'])) {
            $user_role = $_POST['user_role'] === 'admin' ? 'admin' : 'subscriber';
        } else {
            $user_role = $user['user_role'];
        }

        if (!empty($user_password)) {
            $hashed_password = password_hash($user_password, PASSWORD_DEFAULT);
            $stmt = mysqli_prepare($connection, "UPDATE users SET user_firstname = ?, user_lastname = ?, username = ?, user_email = ?, user_role = ?, user_password = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "ssssssi", $user_firstname, $user_lastname, $username, $user_email, $user_role, $hashed_password, $user_id);
        } else {
            $stmt = mysqli_prepare($connection, "UPDATE users SET user_firstname = ?, user_lastname = ?, username = ?, user_email = ?, user_role = ? WHERE user_id = ?");
            mysqli_stmt_bind_param($stmt, "sssssi", $user_firstname, $user_lastname, $username, $user_email, $user_role, $user_id);
        }

        if (mysqli_stmt_execute($stmt)) {
            $_SESSION['username'] = $username;
            $_SESSION['user_role'] = $user_role;
            
            header("Location: profile.php?success=1");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Update failed.</div>";
        }
    }
}

$user_firstname = htmlspecialchars($user['user_firstname'] ?? '');
$user_lastname  = htmlspecialchars($user['user_lastname'] ?? '');
$username       = htmlspecialchars($user['username']);
$user_email     = htmlspecialchars($user['user_email']);
$user_role      = $user['user_role'];
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">My Profile</h2>

    <?php echo $message; ?>

    <form method="post" autocomplete="off">
        <div class="row">
            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Firstname</label>
                    <input type="text" class="form-control" name="user_firstname" value="<?php echo $user_firstname; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Lastname</label>
                    <input type="text" class="form-control" name="user_lastname" value="<?php echo $user_lastname; ?>">
                </div>

                <div class="mb-3">
                    <label class="form-label">Role</label>
                    <select name="user_role" class="form-select" <?php echo ($_SESSION['user_role'] !== 'admin') ? 'disabled' : ''; ?>>
                        <option value="admin" <?php echo ($user_role === 'admin') ? 'selected' : ''; ?>>Admin</option>
                        <option value="subscriber" <?php echo ($user_role === 'subscriber') ? 'selected' : ''; ?>>Subscriber</option>
                    </select>
                    <?php if ($_SESSION['user_role'] !== 'admin'): ?>
                        <input type="hidden" name="user_role" value="<?php echo $user_role; ?>">
                    <?php endif; ?>
                </div>
            </div>

            <div class="col-md-6">
                <div class="mb-3">
                    <label class="form-label">Username</label>
                    <input type="text" class="form-control" name="username" value="<?php echo $username; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" class="form-control" name="user_email" value="<?php echo $user_email; ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">New Password</label>
                    <input type="password" class="form-control" name="user_password" placeholder="Leave blank to keep current password">
                </div>
            </div>
        </div>

        <button class="btn btn-primary" type="submit">Update Profile</button>
    </form>
</div>

<?php include "includes/admin_footer.php"; ?>  
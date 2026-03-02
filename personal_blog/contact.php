<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $email = trim($_POST['email']);
    $subject = trim($_POST['subject']);
    $body = trim($_POST['body']);

    if (empty($email) || empty($subject) || empty($body)) {
        $message = "<div class='alert alert-danger'>All fields are required.</div>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message = "<div class='alert alert-danger'>Invalid email address.</div>";
    } else {
        $safe_email = htmlspecialchars($email);
        $safe_subject = htmlspecialchars($subject);
        $safe_body = htmlspecialchars($body);
        $message = "<div class='alert alert-success'>Thank you for contacting us. We will respond soon.</div>";
    }
}
?>

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <h1 class="text-center mb-4">Contact Me</h1>

            <?php echo $message; ?>

            <form action="" method="post" autocomplete="off">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" name="email" id="email" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="subject" class="form-label">Subject</label>
                    <input type="text" name="subject" id="subject" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="body" class="form-label">Message</label>
                    <textarea class="form-control" name="body" id="body" rows="6" required></textarea>
                </div>

                <input type="submit" name="submit" class="btn btn-primary w-100" value="Send Message">
            </form>
        </div>
    </div>
</div>

<?php include "includes/footer.php"; ?>
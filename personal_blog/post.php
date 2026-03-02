<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container">
    <div class="row">
        <div class="col-lg-8">

            <?php
            if (!isset($_GET['p_id']) || !is_numeric($_GET['p_id'])) {
                header("Location: index.php");
                exit();
            }

            $the_post_id = (int) $_GET['p_id'];

            $update_stmt = mysqli_prepare($connection, "UPDATE posts SET post_views_count = post_views_count + 1 WHERE post_id = ?");
            mysqli_stmt_bind_param($update_stmt, "i", $the_post_id);
            mysqli_stmt_execute($update_stmt);

            $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_id = ? AND post_status = ?");
            $status = "published";
            mysqli_stmt_bind_param($stmt, "is", $the_post_id, $status);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo "<h4 class='text-danger'>Post not found.</h4>";
            } else {

                $row = mysqli_fetch_assoc($result);

                $post_title = htmlspecialchars($row['post_title']);
                $post_author = htmlspecialchars($row['post_author']);
                $post_date = $row['post_date'];
                $post_image = htmlspecialchars($row['post_image']);
                $post_content = $row['post_content']; 
            ?>

                <h1 class="mt-4"><?php echo $post_title; ?></h1>

                <p class="text-muted">
                    By 
                    <a href="author_posts.php?author=<?php echo urlencode($post_author); ?>">
                        <?php echo $post_author; ?>
                    </a> 
                    | <?php echo $post_date; ?>
                </p>

                <hr>

                <?php if (!empty($post_image)) { ?>
                    <img class="img-fluid mb-4 rounded" src="assets/uploads/<?php echo $post_image; ?>" alt="">
                <?php } ?>

                <p><?php echo $post_content; ?></p>

                <hr>

                <?php
                if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_comment'])) {
                    
                    if (isset($_SESSION['user_id'])) {
                        $session_user_id = (int)$_SESSION['user_id'];
                        $user_query = mysqli_query($connection, "SELECT username, user_email FROM users WHERE user_id = {$session_user_id}");
                        $user_data = mysqli_fetch_assoc($user_query);
                        
                        $comment_author = $user_data['username'];
                        $comment_email = $user_data['user_email'];
                    } else {
                        $comment_author = trim($_POST['comment_author']);
                        $comment_email = trim($_POST['comment_email']);
                    }

                    $comment_content = trim($_POST['comment_content']);

                    if (!empty($comment_author) && !empty($comment_email) && !empty($comment_content)) {

                        $stmt_comment = mysqli_prepare($connection, "INSERT INTO comments (comment_post_id, comment_author, comment_email, comment_content, comment_status, comment_date) VALUES (?, ?, ?, ?, 'unapproved', NOW())");
                        mysqli_stmt_bind_param($stmt_comment, "isss", $the_post_id, $comment_author, $comment_email, $comment_content);
                        mysqli_stmt_execute($stmt_comment);

                        echo "<div class='alert alert-success'>Your comment has been submitted and is awaiting approval.</div>";
                    } else {
                        echo "<div class='alert alert-danger'>Fields cannot be empty.</div>";
                    }
                }
                ?>

                <div class="card my-4 bg-light">
                    <h5 class="card-header">Leave a Comment:</h5>
                    <div class="card-body">
                        <form method="post">
                            
                            <?php if (!isset($_SESSION['user_id'])): ?>
                                <div class="mb-3">
                                    <label class="form-label">Name</label>
                                    <input type="text" class="form-control" name="comment_author" required>
                                </div>
                                <div class="mb-3">
                                    <label class="form-label">Email</label>
                                    <input type="email" class="form-control" name="comment_email" required>
                                </div>
                            <?php else: ?>
                                <p class="text-muted small mb-2">Commenting as <strong><?php echo htmlspecialchars($_SESSION['username']); ?></strong></p>
                            <?php endif; ?>

                            <div class="mb-3">
                                <label class="form-label">Comment</label>
                                <textarea class="form-control" rows="3" name="comment_content" required></textarea>
                            </div>
                            <button type="submit" name="create_comment" class="btn btn-primary">Submit</button>
                        </form>
                    </div>
                </div>

                <?php
                $query = "SELECT * FROM comments WHERE comment_post_id = ? AND comment_status = 'approved' ORDER BY comment_id DESC";
                $stmt_fetch_comments = mysqli_prepare($connection, $query);
                mysqli_stmt_bind_param($stmt_fetch_comments, "i", $the_post_id);
                mysqli_stmt_execute($stmt_fetch_comments);
                $comment_result = mysqli_stmt_get_result($stmt_fetch_comments);

                while ($comment_row = mysqli_fetch_assoc($comment_result)) {
                    $comment_date = $comment_row['comment_date'];
                    $comment_content = htmlspecialchars($comment_row['comment_content']);
                    $comment_author = htmlspecialchars($comment_row['comment_author']);
                ?>

                    <div class="d-flex mb-4">
                        <div class="flex-shrink-0">
                            <img class="rounded-circle" src="https://dummyimage.com/50x50/ced4da/6c757d.jpg" alt="..." />
                        </div>
                        <div class="ms-3">
                            <div class="fw-bold"><?php echo $comment_author; ?> <small class="text-muted"><?php echo $comment_date; ?></small></div>
                            <?php echo $comment_content; ?>
                        </div>
                    </div>

                <?php } ?>

            <?php } ?>

        </div>

        <?php include "includes/sidebar.php"; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>
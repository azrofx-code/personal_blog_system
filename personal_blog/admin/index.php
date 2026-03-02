<?php 
include "includes/admin_header.php"; 
include "includes/admin_navigation.php"; 
requireAdmin();
?>

<div class="container-fluid mt-4">

    <h2 class="mb-4">Admin Dashboard <small class="text-muted"><?php echo htmlspecialchars($_SESSION['username']); ?></small></h2>

    <div class="row">

        <?php
        $post_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM posts");
        $post_count = mysqli_fetch_assoc($post_result)['total'];

        $category_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM categories");
        $category_count = mysqli_fetch_assoc($category_result)['total'];

        $user_result = mysqli_query($connection, "SELECT COUNT(*) AS total FROM users");
        $user_count = mysqli_fetch_assoc($user_result)['total'];
        ?>

        <div class="col-md-4">
            <div class="card text-white bg-primary mb-4">
                <div class="card-body text-center">
                    <h3><?php echo $post_count; ?></h3>
                    <p class="mb-0">Posts</p>
                </div>
                <div class="card-footer text-center">
                    <a href="posts.php" class="text-white text-decoration-none">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-success mb-4">
                <div class="card-body text-center">
                    <h3><?php echo $category_count; ?></h3>
                    <p class="mb-0">Categories</p>
                </div>
                <div class="card-footer text-center">
                    <a href="categories.php" class="text-white text-decoration-none">View Details</a>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card text-white bg-warning mb-4">
                <div class="card-body text-center">
                    <h3><?php echo $user_count; ?></h3>
                    <p class="mb-0">Users</p>
                </div>
                <div class="card-footer text-center">
                    <a href="users.php" class="text-white text-decoration-none">View Details</a>
                </div>
            </div>
        </div>

    </div>

</div>

<?php include "includes/admin_footer.php"; ?>
<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">

            <?php
            if (!isset($_GET['category']) || empty($_GET['category']) || !is_numeric($_GET['category'])) {
                echo "<h4 class='text-danger'>Invalid category.</h4>";
                exit();
            }

            $post_category_id = (int) $_GET['category'];

            $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_category_id = ? ORDER BY post_date DESC");
            mysqli_stmt_bind_param($stmt, "i", $post_category_id);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo "<h4 class='text-warning'>No posts available in this category.</h4>";
            }

            while ($row = mysqli_fetch_assoc($result)) {

                $post_id = $row['post_id'];
                $post_title = htmlspecialchars($row['post_title']);
                $post_author = htmlspecialchars($row['post_author']);
                $post_date = $row['post_date'];
                $post_image = htmlspecialchars($row['post_image']);
                $post_content = htmlspecialchars(substr($row['post_content'], 0, 150));
            ?>

                <div class="card mb-4">
                    <?php if (!empty($post_image)) { ?>
                        <img class="card-img-top" src="assets/uploads/<?php echo $post_image; ?>" alt="">
                    <?php } ?>

                    <div class="card-body">
                        <h3 class="card-title">
                            <a href="post.php?p_id=<?php echo $post_id; ?>">
                                <?php echo $post_title; ?>
                            </a>
                        </h3>

                        <p class="text-muted">
                            By <?php echo $post_author; ?> | <?php echo $post_date; ?>
                        </p>

                        <p class="card-text">
                            <?php echo $post_content; ?>...
                        </p>

                        <a class="btn btn-primary" href="post.php?p_id=<?php echo $post_id; ?>">
                            Read More
                        </a>
                    </div>
                </div>

            <?php } ?>

        </div>

        <?php include "includes/sidebar.php"; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>
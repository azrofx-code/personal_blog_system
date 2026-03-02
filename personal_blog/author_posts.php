<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">

            <?php
            if (!isset($_GET['author']) || empty($_GET['author'])) {
                echo "<h4 class='text-danger'>Author not specified.</h4>";
                exit();
            }

            $the_post_author = $_GET['author'];

            
            $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_author = ? ORDER BY post_date DESC");
            mysqli_stmt_bind_param($stmt, "s", $the_post_author);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);

            if (mysqli_num_rows($result) == 0) {
                echo "<h4 class='text-warning'>No posts found for this author.</h4>";
            }

            while ($row = mysqli_fetch_assoc($result)) {

                $post_id = $row['post_id'];
                $post_title = htmlspecialchars($row['post_title']);
                $post_author = htmlspecialchars($row['post_author']);
                $post_date = $row['post_date'];
                $post_image = htmlspecialchars($row['post_image']);
                $post_content = htmlspecialchars(substr($row['post_content'], 0, 200));
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
                            By <a href="author_posts.php?author=<?php echo urlencode($post_author); ?>">
                                <?php echo $post_author; ?>
                            </a> | <?php echo $post_date; ?>
                        </p>

                        <p class="card-text">
                            <?php echo $post_content; ?>...
                        </p>

                        <a href="post.php?p_id=<?php echo $post_id; ?>" class="btn btn-primary">
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
<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container">
    <div class="row">
        <div class="col-md-8">

            <?php
            if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {

                $search = trim($_POST['search']);

                if (empty($search)) {
                    echo "<div class='alert alert-danger'>Please enter a search term.</div>";
                } else {

                    $search_term = "%" . $search . "%";
                    $status = "published";

                    $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_status = ? AND post_tags LIKE ? ORDER BY post_date DESC");
                    mysqli_stmt_bind_param($stmt, "ss", $status, $search_term);
                    mysqli_stmt_execute($stmt);
                    $result = mysqli_stmt_get_result($stmt);

                    if (mysqli_num_rows($result) == 0) {
                        echo "<div class='alert alert-warning'>No results found.</div>";
                    } else {

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
                                    <a href="post.php?p_id=<?php echo $post_id; ?>">
                                        <img class="card-img-top" src="assets/uploads/<?php echo $post_image; ?>" alt="">
                                    </a>
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

            <?php
                        }
                    }
                }
            } else {
                echo "<div class='alert alert-info'>Use the search form to find posts.</div>";
            }
            ?>

        </div>

        <?php include "includes/sidebar.php"; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>
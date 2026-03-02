<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container my-4">
    <div class="row">
        <div class="col-md-8">
            <h1 class="mb-4 pb-2 border-bottom">The Blog</h1>

            <?php
            $per_page = 5;

            if (isset($_GET['page'])) {
                $page = (int)$_GET['page'];
            } else {
                $page = 1;
            }

            if ($page === "" || $page === 1) {
                $page_1 = 0;
            } else {
                $page_1 = ($page * $per_page) - $per_page;
            }

            $post_query_count = "SELECT post_id FROM posts WHERE post_status = 'published'";
            $find_count = mysqli_query($connection, $post_query_count);
            $count = mysqli_num_rows($find_count);
            $count = ceil($count / $per_page);

            if ($count == 0) {
                echo "<h4 class='text-warning mt-4'>No posts available.</h4>";
            } else {
                $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_status = ? ORDER BY post_date DESC LIMIT ?, ?");
                $status = "published";
                mysqli_stmt_bind_param($stmt, "sii", $status, $page_1, $per_page);
                mysqli_stmt_execute($stmt);
                $result = mysqli_stmt_get_result($stmt);

                while ($row = mysqli_fetch_assoc($result)) {
                    $post_id = $row['post_id'];
                    $post_title = htmlspecialchars($row['post_title']);
                    $post_author = htmlspecialchars($row['post_author']);
                    $post_date = date('F j, Y', strtotime($row['post_date']));
                    $post_image = htmlspecialchars($row['post_image']);
                    
                    $post_content = strip_tags(htmlspecialchars_decode($row['post_content'])); 
                    $post_content = substr($post_content, 0, 200);
            ?>

                    <div class="card mb-5 border-0 shadow-sm">
                        <?php if (!empty($post_image)) { ?>
                            <a href="post.php?p_id=<?php echo $post_id; ?>">
                                <img class="card-img-top" src="assets/uploads/<?php echo $post_image; ?>" alt="">
                            </a>
                        <?php } ?>
                        <div class="card-body p-4">
                            <h2 class="card-title">
                                <a href="post.php?p_id=<?php echo $post_id; ?>" class="text-decoration-none text-dark"><?php echo $post_title; ?></a>
                            </h2>
                            <p class="text-muted mb-3">
                                <span class="badge bg-light text-dark me-2">By <?php echo $post_author; ?></span> 
                                <small>Published on <?php echo $post_date; ?></small>
                            </p>
                            <p class="card-text text-muted fs-5"><?php echo $post_content; ?>...</p>
                            <a class="btn btn-primary mt-2" href="post.php?p_id=<?php echo $post_id; ?>">Read Full Article</a>
                        </div>
                    </div>

            <?php 
                } 
            }
            ?>

            <ul class="pagination justify-content-center mt-5 mb-5">
                <?php
                for ($i = 1; $i <= $count; $i++) {
                    if ($i == $page) {
                        echo "<li class='page-item active'><a class='page-link' href='blog.php?page={$i}'>{$i}</a></li>";
                    } else {
                        echo "<li class='page-item'><a class='page-link' href='blog.php?page={$i}'>{$i}</a></li>";
                    }
                }
                ?>
            </ul>

        </div>

        <?php include "includes/sidebar.php"; ?>

    </div>
</div>

<?php include "includes/footer.php"; ?>
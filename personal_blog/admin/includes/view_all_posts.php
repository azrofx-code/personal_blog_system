<?php
requireAdmin();

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $post_id = (int) $_GET['delete'];

    $img_stmt = mysqli_prepare($connection, "SELECT post_image FROM posts WHERE post_id = ?");
    mysqli_stmt_bind_param($img_stmt, "i", $post_id);
    mysqli_stmt_execute($img_stmt);
    $img_result = mysqli_stmt_get_result($img_stmt);
    $img_row = mysqli_fetch_assoc($img_result);

    if ($img_row && !empty($img_row['post_image'])) {
        $image_path = "../assets/uploads/" . $img_row['post_image'];
        if (file_exists($image_path)) {
            unlink($image_path);
        }
    }

    $stmt = mysqli_prepare($connection, "DELETE FROM posts WHERE post_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $post_id);
    mysqli_stmt_execute($stmt);

    header("Location: posts.php");
    exit();
}

$query = "
    SELECT posts.*, categories.cat_title 
    FROM posts 
    LEFT JOIN categories ON posts.post_category_id = categories.cat_id 
    ORDER BY posts.post_id DESC
";

$result = mysqli_query($connection, $query);
?>

<div class="container-fluid">
    <h3 class="mb-4">All Posts</h3>
    <table class="table table-bordered table-hover shadow-sm bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Title</th>
                <th>Category</th>
                <th>Status</th>
                <th>Image</th>
                <th>Tags</th>
                <th>Date</th>
                <th>Views</th>
                <th>Edit</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)):
                $post_id = (int)$row['post_id'];
                $post_author = htmlspecialchars($row['post_author']);
                $post_title = htmlspecialchars($row['post_title']);
                $post_category = htmlspecialchars($row['cat_title']);
                $post_status = htmlspecialchars($row['post_status']);
                $post_image = htmlspecialchars($row['post_image']);
                $post_tags = htmlspecialchars($row['post_tags']);
                $post_date = htmlspecialchars($row['post_date']);
                $post_views_count = (int)$row['post_views_count'];
            ?>
                <tr>
                    <td><?php echo $post_id; ?></td>
                    <td><?php echo $post_author; ?></td>
                    <td><?php echo $post_title; ?></td>
                    <td><?php echo $post_category; ?></td>
                    <td>
                        <span class="badge <?php echo $post_status === 'published' ? 'bg-success' : 'bg-secondary'; ?>">
                            <?php echo ucfirst($post_status); ?>
                        </span>
                    </td>
                    <td>
                        <?php if (!empty($post_image)) { ?>
                            <img src="../assets/uploads/<?php echo $post_image; ?>" width="80" class="rounded">
                        <?php } ?>
                    </td>
                    <td><?php echo $post_tags; ?></td>
                    <td><?php echo $post_date; ?></td>
                    <td><?php echo $post_views_count; ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-primary" href="posts.php?source=edit_post&p_id=<?php echo $post_id; ?>">Edit</a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this post and its image permanently?');" href="posts.php?delete=<?php echo $post_id; ?>">Delete</a>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>
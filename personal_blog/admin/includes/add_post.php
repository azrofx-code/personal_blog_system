<?php
requireAdmin();

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['create_post'])) {

    $post_title = trim($_POST['title']);
    $post_category_id = (int) $_POST['post_category'];
    $post_status = $_POST['post_status'] === 'published' ? 'published' : 'draft';
    $post_tags = trim($_POST['post_tags']);
    $post_content = trim($_POST['post_content']);
    $post_author = $_SESSION['username'];

    if (empty($post_title) || empty($post_content)) {
        $message = "<div class='alert alert-danger'>Title and content are required.</div>";
    } else {
        $post_image = "";

        if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
            $tmp_name = $_FILES['image']['tmp_name'];
            
            $finfo = finfo_open(FILEINFO_MIME_TYPE);
            $mime_type = finfo_file($finfo, $tmp_name);
            finfo_close($finfo);

            $allowed_mimes = [
                'image/jpeg' => 'jpg',
                'image/png' => 'png',
                'image/webp' => 'webp',
                'image/gif' => 'gif'
            ];

            if (array_key_exists($mime_type, $allowed_mimes)) {
                $extension = $allowed_mimes[$mime_type];
                $post_image = uniqid('img_', true) . "." . $extension;
                move_uploaded_file($tmp_name, "../assets/uploads/" . $post_image);
            } else {
                $message = "<div class='alert alert-danger'>Invalid image format.</div>";
            }
        }

        if (empty($message)) {
            $stmt = mysqli_prepare($connection, 
                "INSERT INTO posts 
                (post_category_id, post_title, post_author, post_date, post_image, post_content, post_tags, post_status) 
                VALUES (?, ?, ?, NOW(), ?, ?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "issssss",
                $post_category_id,
                $post_title,
                $post_author,
                $post_image,
                $post_content,
                $post_tags,
                $post_status
            );

            if (mysqli_stmt_execute($stmt)) {
                $message = "<div class='alert alert-success'>Post created successfully. <a href='posts.php'>View Posts</a></div>";
            } else {
                $message = "<div class='alert alert-danger'>Failed to create post.</div>";
            }
        }
    }
}
?>

<div class="container-fluid">
    <h3 class="mb-4">Add New Post</h3>
    <?php echo $message; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Post Title</label>
            <input type="text" class="form-control" name="title" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="post_category" class="form-select" required>
                <?php
                $result = mysqli_query($connection, "SELECT cat_id, cat_title FROM categories ORDER BY cat_title ASC");
                while ($row = mysqli_fetch_assoc($result)) {
                    $cat_id = (int)$row['cat_id'];
                    $cat_title = htmlspecialchars($row['cat_title']);
                    echo "<option value='{$cat_id}'>{$cat_title}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Post Status</label>
            <select name="post_status" class="form-select">
                <option value="published">Published</option>
                <option value="draft">Draft</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Post Image</label>
            <input type="file" class="form-control" name="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Post Tags</label>
            <input type="text" class="form-control" name="post_tags">
        </div>
        <div class="mb-3">
            <label class="form-label">Post Content</label>
            <textarea class="form-control" name="post_content" rows="8" required></textarea>
        </div>
        <button class="btn btn-primary" type="submit" name="create_post">
            Publish Post
        </button>
    </form>
</div>
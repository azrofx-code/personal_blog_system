<?php
requireAdmin();

if (!isset($_GET['p_id']) || !is_numeric($_GET['p_id'])) {
    header("Location: posts.php");
    exit();
}

$the_post_id = (int) $_GET['p_id'];

$stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_id = ?");
mysqli_stmt_bind_param($stmt, "i", $the_post_id);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);

if (mysqli_num_rows($result) !== 1) {
    header("Location: posts.php");
    exit();
}

$post = mysqli_fetch_assoc($result);
$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['update_post'])) {

    $post_title = trim($_POST['post_title']);
    $post_category_id = (int) $_POST['post_category'];
    $post_status = $_POST['post_status'] === 'published' ? 'published' : 'draft';
    $post_tags = trim($_POST['post_tags']);
    $post_content = trim($_POST['post_content']);
    
    $post_image = $post['post_image'];

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
            $new_image = uniqid('img_', true) . "." . $extension;
            
            if (move_uploaded_file($tmp_name, "../assets/uploads/" . $new_image)) {
                $post_image = $new_image;
            }
        } else {
            $message = "<div class='alert alert-danger'>Invalid image format.</div>";
        }
    }

    if (empty($message)) {
        $stmt = mysqli_prepare($connection,
            "UPDATE posts SET 
                post_title = ?, 
                post_category_id = ?, 
                post_status = ?, 
                post_tags = ?, 
                post_content = ?, 
                post_image = ?
             WHERE post_id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sissssi",
            $post_title,
            $post_category_id,
            $post_status,
            $post_tags,
            $post_content,
            $post_image,
            $the_post_id
        );

        if (mysqli_stmt_execute($stmt)) {
            header("Location: posts.php");
            exit();
        } else {
            $message = "<div class='alert alert-danger'>Update failed.</div>";
        }
    }
}

$post_title = htmlspecialchars($post['post_title']);
$post_category_id = (int)$post['post_category_id'];
$post_status = htmlspecialchars($post['post_status']);
$post_tags = htmlspecialchars($post['post_tags']);
$post_content = htmlspecialchars($post['post_content']);
$post_image = htmlspecialchars($post['post_image']);
?>

<div class="container-fluid">
    <h3 class="mb-4">Edit Post</h3>
    <?php echo $message; ?>
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label class="form-label">Post Title</label>
            <input type="text" class="form-control" name="post_title" value="<?php echo $post_title; ?>" required>
        </div>
        <div class="mb-3">
            <label class="form-label">Category</label>
            <select name="post_category" class="form-select" required>
                <?php
                $result = mysqli_query($connection, "SELECT cat_id, cat_title FROM categories ORDER BY cat_title ASC");
                while ($row = mysqli_fetch_assoc($result)) {
                    $cat_id = (int)$row['cat_id'];
                    $cat_title = htmlspecialchars($row['cat_title']);
                    $selected = ($cat_id === $post_category_id) ? "selected" : "";
                    echo "<option value='{$cat_id}' {$selected}>{$cat_title}</option>";
                }
                ?>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Post Status</label>
            <select name="post_status" class="form-select">
                <option value="published" <?php echo ($post_status === 'published') ? 'selected' : ''; ?>>Published</option>
                <option value="draft" <?php echo ($post_status === 'draft') ? 'selected' : ''; ?>>Draft</option>
            </select>
        </div>
        <div class="mb-3">
            <label class="form-label">Current Image</label><br>
            <?php if (!empty($post_image)) { ?>
                <img src="../assets/uploads/<?php echo $post_image; ?>" width="120" class="mb-2 rounded shadow-sm">
            <?php } ?>
            <input type="file" class="form-control" name="image" accept="image/*">
        </div>
        <div class="mb-3">
            <label class="form-label">Post Tags</label>
            <input type="text" class="form-control" name="post_tags" value="<?php echo $post_tags; ?>">
        </div>
        <div class="mb-3">
            <label class="form-label">Post Content</label>
            <textarea class="form-control" name="post_content" rows="8" required><?php echo $post_content; ?></textarea>
        </div>
        <button class="btn btn-primary" type="submit" name="update_post">
            Update Post
        </button>
    </form>
</div>
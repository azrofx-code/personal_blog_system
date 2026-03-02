<?php
include "includes/admin_header.php";
include "includes/admin_navigation.php";
requireAdmin();

if (isset($_GET['approve']) && is_numeric($_GET['approve'])) {
    $the_comment_id = (int) $_GET['approve'];

    $stmt_get = mysqli_prepare($connection, "SELECT comment_post_id FROM comments WHERE comment_id = ?");
    mysqli_stmt_bind_param($stmt_get, "i", $the_comment_id);
    mysqli_stmt_execute($stmt_get);
    $result_get = mysqli_stmt_get_result($stmt_get);
    
    if ($row = mysqli_fetch_assoc($result_get)) {
        $post_id = $row['comment_post_id'];
        
        $stmt_update = mysqli_prepare($connection, "UPDATE comments SET comment_status = 'approved' WHERE comment_id = ?");
        mysqli_stmt_bind_param($stmt_update, "i", $the_comment_id);
        mysqli_stmt_execute($stmt_update);
        
        $stmt_count = mysqli_prepare($connection, "UPDATE posts SET post_comment_count = (SELECT COUNT(*) FROM comments WHERE comment_post_id = ? AND comment_status = 'approved') WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt_count, "ii", $post_id, $post_id);
        mysqli_stmt_execute($stmt_count);
    }
    
    header("Location: comments.php");
    exit();
}

if (isset($_GET['unapprove']) && is_numeric($_GET['unapprove'])) {
    $the_comment_id = (int) $_GET['unapprove'];

    $stmt_get = mysqli_prepare($connection, "SELECT comment_post_id FROM comments WHERE comment_id = ?");
    mysqli_stmt_bind_param($stmt_get, "i", $the_comment_id);
    mysqli_stmt_execute($stmt_get);
    $result_get = mysqli_stmt_get_result($stmt_get);
    
    if ($row = mysqli_fetch_assoc($result_get)) {
        $post_id = $row['comment_post_id'];
        
        $stmt_update = mysqli_prepare($connection, "UPDATE comments SET comment_status = 'unapproved' WHERE comment_id = ?");
        mysqli_stmt_bind_param($stmt_update, "i", $the_comment_id);
        mysqli_stmt_execute($stmt_update);
        
        $stmt_count = mysqli_prepare($connection, "UPDATE posts SET post_comment_count = (SELECT COUNT(*) FROM comments WHERE comment_post_id = ? AND comment_status = 'approved') WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt_count, "ii", $post_id, $post_id);
        mysqli_stmt_execute($stmt_count);
    }

    header("Location: comments.php");
    exit();
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $the_comment_id = (int) $_GET['delete'];

    $stmt_get = mysqli_prepare($connection, "SELECT comment_post_id FROM comments WHERE comment_id = ?");
    mysqli_stmt_bind_param($stmt_get, "i", $the_comment_id);
    mysqli_stmt_execute($stmt_get);
    $result_get = mysqli_stmt_get_result($stmt_get);
    
    if ($row = mysqli_fetch_assoc($result_get)) {
        $post_id = $row['comment_post_id'];
        
        $stmt_delete = mysqli_prepare($connection, "DELETE FROM comments WHERE comment_id = ?");
        mysqli_stmt_bind_param($stmt_delete, "i", $the_comment_id);
        mysqli_stmt_execute($stmt_delete);
        
        $stmt_count = mysqli_prepare($connection, "UPDATE posts SET post_comment_count = (SELECT COUNT(*) FROM comments WHERE comment_post_id = ? AND comment_status = 'approved') WHERE post_id = ?");
        mysqli_stmt_bind_param($stmt_count, "ii", $post_id, $post_id);
        mysqli_stmt_execute($stmt_count);
    }

    header("Location: comments.php");
    exit();
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Comments Management</h2>

    <table class="table table-bordered table-hover shadow-sm bg-white">
        <thead class="table-dark">
            <tr>
                <th>ID</th>
                <th>Author</th>
                <th>Comment</th>
                <th>Email</th>
                <th>Status</th>
                <th>In Response To</th>
                <th>Date</th>
                <th>Approve</th>
                <th>Unapprove</th>
                <th>Delete</th>
            </tr>
        </thead>
        <tbody>
            <?php
            $query = "SELECT comments.*, posts.post_title, posts.post_id 
                      FROM comments 
                      LEFT JOIN posts ON comments.comment_post_id = posts.post_id 
                      ORDER BY comments.comment_id DESC";
            $result = mysqli_query($connection, $query);

            while ($row = mysqli_fetch_assoc($result)) {
                $comment_id = (int)$row['comment_id'];
                $comment_author = htmlspecialchars($row['comment_author']);
                $comment_content = htmlspecialchars($row['comment_content']);
                $comment_email = htmlspecialchars($row['comment_email']);
                $comment_status = htmlspecialchars($row['comment_status']);
                $comment_date = htmlspecialchars($row['comment_date']);
                $post_title = htmlspecialchars($row['post_title']);
                $post_id = (int)$row['post_id'];
            ?>
                <tr>
                    <td><?php echo $comment_id; ?></td>
                    <td><?php echo $comment_author; ?></td>
                    <td><?php echo $comment_content; ?></td>
                    <td><?php echo $comment_email; ?></td>
                    <td>
                        <span class="badge <?php echo $comment_status === 'approved' ? 'bg-success' : 'bg-warning text-dark'; ?>">
                            <?php echo ucfirst($comment_status); ?>
                        </span>
                    </td>
                    <td><a href="../post.php?p_id=<?php echo $post_id; ?>"><?php echo $post_title; ?></a></td>
                    <td><?php echo $comment_date; ?></td>
                    <td>
                        <a class="btn btn-sm btn-outline-success" href="comments.php?approve=<?php echo $comment_id; ?>">Approve</a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-outline-warning" href="comments.php?unapprove=<?php echo $comment_id; ?>">Unapprove</a>
                    </td>
                    <td>
                        <a class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this comment?');" href="comments.php?delete=<?php echo $comment_id; ?>">Delete</a>
                    </td>
                </tr>
            <?php } ?>
        </tbody>
    </table>
</div>

<?php include "includes/admin_footer.php"; ?>
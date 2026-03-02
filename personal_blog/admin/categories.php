<?php 
include "includes/admin_header.php"; 
include "includes/admin_navigation.php"; 

if (!isset($_SESSION['user_role']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../index.php");
    exit();
}

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['cat_title'])) {

    $cat_title = trim($_POST['cat_title']);

    if (empty($cat_title)) {
        $message = "<div class='alert alert-danger'>Category title cannot be empty.</div>";
    } else {
        $stmt = mysqli_prepare($connection, "INSERT INTO categories (cat_title) VALUES (?)");
        mysqli_stmt_bind_param($stmt, "s", $cat_title);
        $execute = mysqli_stmt_execute($stmt);

        if ($execute) {
            $message = "<div class='alert alert-success'>Category added successfully.</div>";
        } else {
            $message = "<div class='alert alert-danger'>Failed to add category.</div>";
        }
    }
}

if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {

    $the_cat_id = (int) $_GET['delete'];

    $stmt = mysqli_prepare($connection, "DELETE FROM categories WHERE cat_id = ?");
    mysqli_stmt_bind_param($stmt, "i", $the_cat_id);
    mysqli_stmt_execute($stmt);

    header("Location: categories.php");
    exit();
}
?>

<div class="container-fluid mt-4">
    <h2 class="mb-4">Manage Categories</h2>

    <?php echo $message; ?>

    <div class="row">

        <div class="col-md-5">
            <form action="" method="post">
                <div class="mb-3">
                    <label class="form-label">Add Category</label>
                    <input type="text" class="form-control" name="cat_title" required>
                </div>
                <button class="btn btn-primary" type="submit">Add Category</button>
            </form>
        </div>

        <div class="col-md-7">
            <table class="table table-bordered table-hover">
                <thead class="table-dark">
                    <tr>
                        <th>ID</th>
                        <th>Category Title</th>
                        <th>Delete</th>
                    </tr>
                </thead>
                <tbody>

                    <?php
                    $result = mysqli_query($connection, "SELECT * FROM categories ORDER BY cat_id DESC");

                    while ($row = mysqli_fetch_assoc($result)) {

                        $cat_id = (int)$row['cat_id'];
                        $cat_title = htmlspecialchars($row['cat_title']);

                        echo "<tr>";
                        echo "<td>{$cat_id}</td>";
                        echo "<td>{$cat_title}</td>";
                        echo "<td><a class='btn btn-sm btn-danger' href='categories.php?delete={$cat_id}' onclick='return confirm(\"Delete this category?\")'>Delete</a></td>";
                        echo "</tr>";
                    }
                    ?>

                </tbody>
            </table>
        </div>

    </div>
</div>

<?php include "includes/admin_footer.php"; ?>
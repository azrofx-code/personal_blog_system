<div class="col-md-4">

    <div class="card mb-4">
        <h5 class="card-header">Search</h5>
        <div class="card-body">
            <form action="search.php" method="post">
                <div class="input-group">
                    <input name="search" type="text" class="form-control" required>
                    <button name="submit" class="btn btn-primary" type="submit">Search</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card mb-4">
        <h5 class="card-header">Categories</h5>
        <div class="card-body">
            <ul class="list-unstyled mb-0">
                <?php
                $categories = fetchCategories($connection);
                while ($row = mysqli_fetch_assoc($categories)) {
                    $cat_title = htmlspecialchars($row['cat_title']);
                    $cat_id = (int)$row['cat_id'];
                    echo "<li><a href='category.php?category={$cat_id}'>{$cat_title}</a></li>";
                }
                ?>
            </ul>
        </div>
    </div>

    <?php include "widget.php"; ?>

</div>
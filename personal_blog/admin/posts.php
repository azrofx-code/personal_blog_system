<?php 
include "includes/admin_header.php"; 
include "includes/admin_navigation.php"; 
requireAdmin();
?>

<div class="container-fluid mt-4">

    <h2 class="mb-4">Post Management</h2>

    <?php

    $allowed_sources = ['add_post', 'edit_post'];
    $source = '';

    if (isset($_GET['source']) && in_array($_GET['source'], $allowed_sources)) {
        $source = $_GET['source'];
    }

    switch ($source) {

        case 'add_post':
            include "includes/add_post.php";
            break;

        case 'edit_post':
            include "includes/edit_post.php";
            break;

        default:
            include "includes/view_all_posts.php";
            break;
    }

    ?>

</div>

<?php include "includes/admin_footer.php"; ?>
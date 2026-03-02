<nav class="navbar navbar-dark bg-dark sticky-top flex-md-nowrap p-0 shadow">
    <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3" href="index.php">
        <?php echo ($_SESSION['user_role'] == 'admin') ? "Admin Panel" : "My Account"; ?>
    </a>
    <ul class="navbar-nav px-3">
        <li class="nav-item text-nowrap">
            <a class="nav-link px-3 text-danger fw-bold" href="../logout.php">Logout</a>
        </li>
    </ul>
</nav>

<div class="container-fluid">
    <div class="row">
        <nav id="sidebarMenu" class="col-md-3 col-lg-2 d-md-block bg-light sidebar collapse shadow-sm">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    
                    <?php if($_SESSION['user_role'] == 'admin'): ?>
                        <li class="nav-item"><a class="nav-link" href="index.php">Dashboard</a></li>
                        <li class="nav-item"><a class="nav-link" href="posts.php">View All Posts</a></li>
                        <li class="nav-item"><a class="nav-link" href="posts.php?source=add_post">Add New Post</a></li>
                        <li class="nav-item"><a class="nav-link" href="categories.php">Categories</a></li>
                        <li class="nav-item"><a class="nav-link" href="users.php">Users</a></li>
                        <li class="nav-item"><a class="nav-link" href="comments.php">Comments</a></li>
                    <?php endif; ?>

                    <li class="nav-item"><a class="nav-link fw-bold text-primary" href="profile.php">Edit My Profile</a></li>
                    
                    <hr>
                    <li class="nav-item">
                        <a class="nav-link" href="../index.php">Back to Website</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-danger" href="../logout.php">Sign Out</a>
                    </li>
                </ul>
            </div>
        </nav>
        <main class="col-md-9 ms-sm-auto col-lg-10 px-md-4 py-4">
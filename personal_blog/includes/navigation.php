<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="index.php">Personal Blog</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarResponsive">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarResponsive">
            <ul class="navbar-nav ms-auto">

                <li class="nav-item">
                    <a class="nav-link" href="index.php">Home</a>
                </li>

                <li class="nav-item">
                    <a class="nav-link text-info" href="blog.php">Blog</a>
                </li>
                
                <li class="nav-item">
                    <a class="nav-link" href="contact.php">Contact</a>
                </li>

                <?php
                $categories = fetchCategories(); 
                while ($row = mysqli_fetch_assoc($categories)) {
                    $cat_title = htmlspecialchars($row['cat_title']);
                    $cat_id = (int)$row['cat_id'];
                    echo "<li class='nav-item'>
                            <a class='nav-link' href='category.php?category={$cat_id}'>{$cat_title}</a>
                          </li>";
                }
                ?>

                <?php if (isset($_SESSION['user_role'])): ?>

                    <?php if ($_SESSION['user_role'] === 'admin'): ?>
                        <li class="nav-item">
                            <a class="nav-link text-warning fw-bold" href="admin/index.php">Admin Panel</a>
                        </li>
                    <?php endif; ?>

                    <li class="nav-item">
                        <a class="nav-link" href="admin/profile.php">My Account</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="logout.php">Logout</a>
                    </li>

                <?php else: ?>

                    <li class="nav-item">
                        <a class="nav-link" href="login.php">Login</a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="registration.php">Register</a>
                    </li>

                <?php endif; ?>

            </ul>
        </div>
    </div>
</nav>
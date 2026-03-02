<?php 
include "includes/header.php"; 
include "includes/navigation.php"; 
?>

<div class="container my-5">
    <div class="row align-items-center rounded-3 border shadow-lg p-4 p-lg-5 text-center text-lg-start bg-white">
        <div class="col-lg-7 p-3 p-lg-5 pt-lg-3">
            <h1 class="display-4 fw-bold lh-1 mb-3">Welcome to My Personal Space</h1>
            <p class="lead">Hi, I'm Abdullah Azrof. I created this space to document my journey, share my thoughts on technology, and connect with other developers.</p>
            <div class="d-grid gap-2 d-md-flex justify-content-md-start mb-4 mb-lg-3">
                <a href="blog.php" class="btn btn-primary btn-lg px-4 me-md-2 fw-bold">Read My Blog</a>
                <a href="contact.php" class="btn btn-outline-secondary btn-lg px-4">Contact Me</a>
            </div>
        </div>
        <div class="col-lg-5 offset-lg-0 p-0 overflow-hidden shadow-lg rounded-3">
            <img class="rounded-3" src="https://dummyimage.com/700x500/0061ff/ffffff.jpg&text=Abdullah+Azrof" alt="Abdullah Azrof" width="100%">
        </div>
    </div>
</div>

<div class="container my-5">
    <div class="row g-4 py-5 row-cols-1 row-cols-lg-2">
        <div class="col d-flex align-items-start">
            <div class="bg-light text-primary flex-shrink-0 me-3 p-3 rounded-circle shadow-sm">
                <h4 class="m-0">👨‍💻</h4>
            </div>
            <div>
                <h2>About Me</h2>
                <p>I am a passionate developer and writer. I love exploring new coding techniques, solving complex backend problems, and building systems from scratch. When I'm not writing code, I'm usually writing articles about it.</p>
            </div>
        </div>
        <div class="col d-flex align-items-start">
            <div class="bg-light text-primary flex-shrink-0 me-3 p-3 rounded-circle shadow-sm">
                <h4 class="m-0">⚙️</h4>
            </div>
            <div>
                <h2>About This Website</h2>
                <p>This website isn't a WordPress template—it is a custom Content Management System (CMS) I built from the ground up using PHP, MySQL, and Bootstrap. It features secure user authentication, an admin dashboard, and dynamic content routing.</p>
            </div>
        </div>
    </div>
</div>

<div class="container my-5">
    <h2 class="pb-2 border-bottom mb-4">Latest Articles</h2>
    <div class="row row-cols-1 row-cols-md-3 g-4">
        
        <?php
        // Fetch only the 3 most recent published posts
        $stmt = mysqli_prepare($connection, "SELECT * FROM posts WHERE post_status = 'published' ORDER BY post_date DESC LIMIT 3");
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (mysqli_num_rows($result) == 0) {
            echo "<div class='col-12'><p class='text-muted'>No posts published yet.</p></div>";
        }

        while ($row = mysqli_fetch_assoc($result)) {
            $post_id = $row['post_id'];
            $post_title = htmlspecialchars($row['post_title']);
            $post_date = date('F j, Y', strtotime($row['post_date']));
            $post_image = htmlspecialchars($row['post_image']);
            
            // Clean HTML tags from the WYSIWYG editor to make a clean text preview snippet
            $post_content = strip_tags(htmlspecialchars_decode($row['post_content'])); 
            $post_content = substr($post_content, 0, 100) . '...';
        ?>
            
            <div class="col">
                <div class="card h-100 shadow-sm border-0">
                    <?php if (!empty($post_image)) { ?>
                        <a href="post.php?p_id=<?php echo $post_id; ?>">
                            <img src="assets/uploads/<?php echo $post_image; ?>" class="card-img-top" alt="..." style="height:200px; object-fit:cover;">
                        </a>
                    <?php } else { ?>
                        <div class="bg-secondary text-white d-flex align-items-center justify-content-center rounded-top" style="height:200px;">No Image</div>
                    <?php } ?>
                    
                    <div class="card-body">
                        <h5 class="card-title">
                            <a href="post.php?p_id=<?php echo $post_id; ?>" class="text-decoration-none text-dark hover-primary"><?php echo $post_title; ?></a>
                        </h5>
                        <p class="text-muted small mb-3"><?php echo $post_date; ?></p>
                        <p class="card-text text-muted"><?php echo $post_content; ?></p>
                    </div>
                    
                    <div class="card-footer bg-white border-0 pb-3 pt-0">
                        <a href="post.php?p_id=<?php echo $post_id; ?>" class="btn btn-sm btn-outline-primary w-100">Read More</a>
                    </div>
                </div>
            </div>

        <?php } ?>
        
    </div>
    
    <?php if (mysqli_num_rows($result) > 0): ?>
        <div class="text-center mt-5">
            <a href="blog.php" class="btn btn-primary btn-lg px-5">View All Posts</a>
        </div>
    <?php endif; ?>
</div>

<?php include "includes/footer.php"; ?>
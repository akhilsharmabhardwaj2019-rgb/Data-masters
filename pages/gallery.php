<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Gallery - Bharat Data Recovery</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../assets/css/style.css">
    <style>
        .gallery-container {
            padding: 100px 5%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .gallery-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(250px, 1fr));
            gap: 20px;
        }

        .gallery-item {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s ease;
            height: 250px;
        }

        .gallery-item:hover {
            transform: scale(1.02);
        }

        .gallery-item img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .empty-gallery {
            text-align: center;
            color: var(--text-dim);
            font-size: 1.2rem;
            margin-top: 50px;
        }
    </style>
</head>

<body>

    <!-- NAVBAR -->
    <nav class="navbar">
        <div class="logo">
            <img src="../assets/images/Logo.png.jpeg" alt="Bharat Data Recovery Logo">
            <span>Bharat Data Recovery</span>
        </div>
        <ul class="nav-links">
            <li><a href="index.html">Home</a></li>
            <li><a href="Services.html">Services</a></li>
            <li><a href="gallery.php" class="active" style="color: var(--primary);">Gallery</a></li>
            <li><a href="contact.html">Contact</a></li>
            <li><a href="../auth/login.html" class="login-btn">Login</a></li>
        </ul>
    </nav>

    <!-- CONTENT -->
    <div class="gallery-container">
        <h2 style="color: var(--primary); margin-bottom: 40px; text-align: center; font-size: 2.5rem;">Our Work Gallery
        </h2>

        <div class="gallery-grid">
            <?php
            $dir = "../assets/images/gallery/";
            if (is_dir($dir)) {
                $files = glob($dir . "*.{jpg,jpeg,png,gif,webp}", GLOB_BRACE);
                if ($files) {
                    foreach ($files as $image) {
                        echo '<div class="gallery-item">';
                        echo '<img src="' . $image . '" alt="Gallery Image">';
                        echo '</div>';
                    }
                } else {
                    echo '<p class="empty-gallery">No images uploaded yet.</p>';
                }
            } else {
                echo '<p class="empty-gallery">Gallery directory not setup.</p>';
            }
            ?>
        </div>
    </div>

</body>

</html>
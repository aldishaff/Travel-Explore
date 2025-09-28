<nav class="navbar">
    <div class="logo">
        <a href="index.php">Explore Travel</a>
    </div>
    <div class="menu" id="menu">
        <a href="index.php" class="menu-item">Home</a>
        <a href="#destinations" class="menu-item">Destinations</a>
        <a href="#about" class="menu-item">About</a>
        <a href="#contact" class="menu-item">Contact Us</a>
        <?php
        session_start();
        if (isset($_SESSION['username'])) {
            echo '<span class="welcome">Hi, ' . htmlspecialchars($_SESSION['username']) . '</span>';
            echo '<a href="login/logout.php" class="menu-item">Sign Out</a>';
        } else {
            echo '<a href="login/sign_in.php" class="menu-item">Sign In</a>';
            echo '<a href="login/sign_up.php" class="menu-item">Sign Up</a>';
        }
        ?>
    </div>
</nav>

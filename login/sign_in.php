<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="background">
        <div class="overlay"></div>
        <div class="form-container">
            <div class="form-header">
                <h1>Welcome Back!</h1>
                <p>Sign in to continue exploring the world with us.</p>
            </div>
            <form action="process_signin.php" method="POST">
    <div class="input-group">
        <input type="email" id="email" name="email" required placeholder=" ">
        <label for="email">Email</label>
    </div>

    <div class="input-group">
        <input type="password" id="password" name="password" required placeholder=" ">
        <label for="password">Password</label>
    </div>

    <button type="submit" class="btn-submit">Sign In</button>
</form>

            <div class="form-footer">
                <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
            </div>
        </div>
    </div>
</body>
</html>

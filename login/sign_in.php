<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign In</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        .error-message {
            color: red;
            font-size: 12px;
            margin-top: 3px;
        }
        .input-group.error input {
            border-color: red;
        }
    </style>
</head>
<body>
    <div class="background">
        <div class="overlay"></div>
        <div class="form-container">
            <div class="form-header">
                <h1>Welcome Back!</h1>
                <p>Sign in to continue exploring the world with us.</p>
            </div>

            <form id="signinForm" action="process_signin.php" method="POST" novalidate>
                <div class="input-group">
                    <input type="email" id="email" name="email" required placeholder=" ">
                    <label for="email">Email</label>
                    <small class="error-message" id="emailError"></small>
                </div>

                <div class="input-group">
                    <input type="password" id="password" name="password" required placeholder=" ">
                    <label for="password">Password</label>
                    <small class="error-message" id="passwordError"></small>
                </div>

                <button type="submit" class="btn-submit">Sign In</button>
            </form>

            <div class="form-footer">
                <p>Don't have an account? <a href="sign_up.php">Sign Up</a></p>
            </div>
        </div>
    </div>

    <script>
document.addEventListener("DOMContentLoaded", function () {

    const form = document.getElementById("signinForm");
    if (!form) return; // Hindari error Cypress jika form belum muncul

    form.addEventListener("submit", function(e) {
        let valid = true;

        const email = document.getElementById("email");
        const password = document.getElementById("password");
        const emailError = document.getElementById("emailError");
        const passwordError = document.getElementById("passwordError");

        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

        [email, password].forEach(el => el.parentElement.classList.remove("error"));
        emailError.textContent = "";
        passwordError.textContent = "";

        if (!emailRegex.test(email.value)) {
            emailError.textContent = "Please enter a valid email address.";
            email.parentElement.classList.add("error");
            valid = false;
        }

        if (!passwordRegex.test(password.value)) {
            passwordError.textContent = "Password must be at least 8 characters with letters and numbers.";
            password.parentElement.classList.add("error");
            valid = false;
        }

        if (!valid) e.preventDefault();
    });

});
</script>

</body>
</html>

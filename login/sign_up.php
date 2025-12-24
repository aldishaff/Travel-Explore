<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>
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
                <h1>Create Your Account</h1>
                <p>Join us and start your adventure today!</p>
            </div>

            <form id="signupForm" action="process_signup.php" method="POST" novalidate>
                <div class="input-group">
                    <input type="text" id="username" name="username" required placeholder=" ">
                    <label for="username">Username</label>
                    <small class="error-message" id="usernameError"></small>
                </div>

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

                <button type="submit" class="btn-submit">Sign Up</button>
            </form>

            <div class="form-footer">
                <p>Already have an account? <a href="sign_in.php">Sign In</a></p>
            </div>
        </div>
    </div>

    <script>
        document.getElementById("signupForm").addEventListener("submit", function(e) {
            let valid = true;

            const username = document.getElementById("username");
            const email = document.getElementById("email");
            const password = document.getElementById("password");
            const usernameError = document.getElementById("usernameError");
            const emailError = document.getElementById("emailError");
            const passwordError = document.getElementById("passwordError");

            const usernameRegex = /^[A-Za-z]{3,}$/;
            const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            const passwordRegex = /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/;

            [username, email, password].forEach(el => el.parentElement.classList.remove("error"));
            usernameError.textContent = "";
            emailError.textContent = "";
            passwordError.textContent = "";

            if (!usernameRegex.test(username.value)) {
                usernameError.textContent = "Username must be at least 3 letters.";
                username.parentElement.classList.add("error");
                valid = false;
            }

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
    </script>
</body>
</html>


<?php

require_once "config.php";

$message = "";
$success = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";
    $confirm_password = $_POST["confirm_password"] ?? "";

    if (empty($username) || empty($password) || empty($confirm_password)) {

        $message = "Please fill in all fields.";

    } elseif (strlen($username) > 50) {

        $message = "Username must not exceed 50 characters.";

    } elseif ($password !== $confirm_password) {

        $message = "Passwords do not match.";

    } else {

        $check = $conn->prepare(
            "SELECT id FROM users WHERE username = ?"
        );

        $check->execute([$username]);

        if ($check->fetch()) {

            $message = "Username already exists.";

        } else {

            $hashed_password = password_hash(
                $password,
                PASSWORD_DEFAULT
            );

            $sql = "INSERT INTO users (username, password)
                    VALUES (?, ?)";

            $stmt = $conn->prepare($sql);

            if ($stmt->execute([$username, $hashed_password])) {

                $message = "Registration successful! Wait for admin approval.";
                $success = true;

            } else {

                $message = "Registration failed.";

            }

        }

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Acadova</title>
    

    <link rel="stylesheet" href="style.css?v=2">
</head>

<body>

<div class="app-shell">

    <header class="topbar">
        <div class="brand">ACADOVA</div>
        <span class="branding-tag">ORIGINAL BRANDING</span>
    </header>

    <main class="auth-layout">

        <section class="auth-art">

            <p class="eyebrow">YOUR SPACE TO SUCCEED</p>

            <h1>
                Start your
                <em>next chapter.</em>
            </h1>

            <p class="auth-intro">
                Create your account and keep your schoolwork organized.
            </p>

        </section>

        <section class="auth-card">

            <h2>Create account</h2>

            <p class="muted">
                Register to begin your study journey.
            </p>

            <?php if (!empty($message)): ?>

                <div class="<?php echo $success ? 'success-message' : 'form-error'; ?>">
                    <?php echo htmlspecialchars($message); ?>
                </div>

            <?php endif; ?>

            <form method="POST" action="register.php">

                <label class="visually-hidden" for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Choose a username"
                    maxlength="50"
                    required
                >

                <label class="visually-hidden" for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Create a password"
                    required
                >

                <label class="visually-hidden" for="confirm_password">
                    Confirm Password
                </label>

                <input
                    type="password"
                    id="confirm_password"
                    name="confirm_password"
                    placeholder="Confirm your password"
                    required
                >

                <button type="submit" class="button">
                    Create account →
                </button>

            </form>

            <p class="auth-switch">
                Already have an account?
                <a href="index.php" class="text-button">
                    Log in
                </a>
            </p>

        </section>

    </main>

    <footer class="app-footer">
        <span>BURGUNDY · GOLD · DARK</span>
        <span>DM Sans</span>
    </footer>

</div>

</body>
</html>


<?php

session_start();
require_once "config.php";

$message = "";

if (isset($_SESSION["user_id"])) {
    header("Location: dashboard.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    $sql = "SELECT * FROM users WHERE username = ?";

    $stmt = $conn->prepare($sql);
    $stmt->execute([$username]);

    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user["password"])) {

        if ($user["status"] == "approved") {

            session_regenerate_id(true);

            $_SESSION["user_id"] = $user["id"];
            $_SESSION["username"] = $user["username"];
            $_SESSION["role"] = $user["role"];

            header("Location: dashboard.php");
            exit();

        } else {

            $message = "Your account is still pending approval.";

        }

    } else {

        $message = "Invalid username or password.";

    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Sign In | Acadova</title>
    

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
                Make room for
                <em>what matters.</em>
            </h1>

            <p class="auth-intro">
                Keep your schoolwork organized, one task at a time.
            </p>

        </section>

        <section class="auth-card">

            <h2>Welcome back</h2>

            <p class="muted">
                Log in to continue your study journey.
            </p>

            <?php if (!empty($message)): ?>
                <div class="form-error">
                    <?php echo htmlspecialchars($message); ?>
                </div>
            <?php endif; ?>

            <form method="POST" action="index.php">

                <label class="visually-hidden" for="username">
                    Username
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Username"
                    required
                >

                <label class="visually-hidden" for="password">
                    Password
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Password"
                    required
                >

                <button type="submit" class="button">
                    Log in →
                </button>

            </form>

            <p class="auth-switch">
                <a href="register.php" class="text-button">
                    Create account
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

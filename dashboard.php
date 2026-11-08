
<?php

session_start();
require_once "config.php";

if (!isset($_SESSION["user_id"])) {
    header("Location: index.php");
    exit();
}

$sql = "SELECT username, role, created_at
        FROM users
        WHERE id = ?";

$stmt = $conn->prepare($sql);
$stmt->execute([$_SESSION["user_id"]]);

$user = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$user) {
    $_SESSION = [];
    session_destroy();

    header("Location: index.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Dashboard | Acadova</title>

    <link rel="stylesheet" href="style.css?v=2">
</head>

<body>

<div class="app-shell">

    <header class="topbar">
        <div class="brand">ACADOVA</div>
    </header>

    <main class="dashboard-wrap">

        <section class="dashboard-card">

            <p class="eyebrow">ACCOUNT MANAGEMENT SYSTEM</p>

            <h1>
                Welcome back,
                <em><?php echo htmlspecialchars($user["username"]); ?>!</em>
            </h1>

            <p class="muted">
                You have successfully signed in to your account.
            </p>

            <div class="info">

                <p class="eyebrow">ACCOUNT INFORMATION</p>

                <div class="info-item">
                    <strong>Username</strong>
                    <span>
                        <?php echo htmlspecialchars($user["username"]); ?>
                    </span>
                </div>

                <div class="info-item">
                    <strong>Role</strong>
                    <span>
                        <?php echo htmlspecialchars($user["role"]); ?>
                    </span>
                </div>

                <div class="info-item">
                    <strong>Registered</strong>
                    <span>
                        <?php echo htmlspecialchars($user["created_at"]); ?>
                    </span>
                </div>

            </div>

            <a href="logout.php" class="button-link">
                Log Out
            </a>

        </section>

    </main>

</div>

</body>
</html>

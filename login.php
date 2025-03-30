<?php
session_start();
require_once 'functions.php';
if ($_SESSION["logged_in"]) {
    header("Location: index.php");
    exit;
}
$result = ['success' => false, 'message' => ''];
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $result = login($_POST["email"], $_POST["password"]);
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Login</title>
    <link href="index.css" type="text/css" rel="stylesheet"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<?php if (!empty($result['message'])): ?>
    <h1 class="message"><?= htmlspecialchars($result['message']) ?></h1>
<?php endif; ?>
<main class="form-container">
    <form method="post" action="login.php">
        <div class="form-group">
            <label for="email">E-MAIL</label>
            <input type="email" name="email" id="email" required>
        </div>
        <div class="form-group">
            <label for="password">PASSWORD</label>
            <input type="password" name="password" id="password" required minlength="8">
        </div>
        <button type="submit">GAIN ACCESS</button>
    </form>
    <p>Don't have an account? <a href="register.php">Register here</a></p>
</main>
</body>
</html>





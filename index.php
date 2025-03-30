<?php
session_start();
if (!isset($_SESSION["logged_in"]) || !$_SESSION["logged_in"]) {
    header("Location: login.php");
    exit;
}
if (isset($_GET['logout'])) {
    session_destroy();
    header("Location: login.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <title>Terminal Access</title>
    <link href="index.css" type="text/css" rel="stylesheet"/>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>
<body>
<header class="header">
    <h1>SYSTEM ACCESS GRANTED</h1>
    <a href="?logout=1" class="logout-btn">TERMINATE SESSION</a>
</header>

<div class="dashboard">
    <div class="terminal-window">
        <h2>USER PROFILE</h2>
        <div class="terminal-content">
            <p>User ID: <?= htmlspecialchars($_SESSION["user_id"]) ?></p>
            <p>Access level: AUTHORIZED</p>
            <p>Last login: <?= date("Y-m-d H:i:s") ?></p>
            <p>Status: ACTIVE</p>
        </div>
    </div>

    <div class="terminal-window">
        <h2>SYSTEM STATUS</h2>
        <div class="terminal-content">
            <p>Server: ONLINE</p>
            <p>Database: CONNECTED</p>
            <p>Encryption: ENABLED</p>
            <p>Security protocol: ACTIVE</p>
        </div>
    </div>

    <div class="terminal-window">
        <h2>RECENT ACTIVITY</h2>
        <div class="terminal-content">
            <p>Login successful - <?= date("H:i:s") ?></p>
            <p>Database query - <?= date("H:i:s", time() - 60) ?></p>
            <p>File access - <?= date("H:i:s", time() - 120) ?></p>
            <p>System scan - <?= date("H:i:s", time() - 300) ?></p>
        </div>
    </div>

    <div class="terminal-window">
        <h2>AVAILABLE COMMANDS</h2>
        <div class="terminal-content">
            <p>NONE
        </div>
    </div>
</div>

</body>
</html>
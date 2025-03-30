<?php
    $host_name = "localhost";
    $user_name = "root";
    $password = "";
    $database = "login";
    function conn() {
        global $host_name, $user_name, $password, $database;
        $conn = new mysqli($host_name, $user_name, $password, $database);
        if ($conn->connect_error) {
            die("Connection failed" . $conn->connect_error);
        }
        return $conn;
    }

function register($email, $password) {
    if (empty($email) || empty($password)) {
        return ["success" => false, "message" => "Email and password cannot be empty"];
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        return ["success" => false, "message" => "Invalid email"];
    }

    if (strlen($password) < 8) {
        return ["success" => false, "message" => "Password must be at least 8 characters"];
    }

    $conn = conn();

    try {
        $conn->begin_transaction();

        $stmt = $conn->prepare("SELECT id_user FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $stmt->store_result();

        if ($stmt->num_rows > 0) {
            $stmt->close();
            $conn->close();
            return ["success" => false, "message" => "User with this email already exists"];
        }
        $stmt->close();

        $password_hash = password_hash($password, PASSWORD_BCRYPT);
        $stmt = $conn->prepare("INSERT INTO users (email, password_hash) VALUES (?, ?)");
        $stmt->bind_param("ss", $email, $password_hash);
        $stmt->execute();
        $stmt->close();

        $conn->commit();
        $conn->close();

        return ["success" => true, "message" => "User registered successfully"];
    } catch (Exception $e) {
        $conn->rollback();
        $conn->close();
        return ["success" => false, "message" => "Registration failed: " . $e->getMessage()];
    }
}

function login($email, $password) {
    if (empty($email) || empty($password)) {
        return ["success" => false, "message" => "Email and password cannot be empty"];
    }

    $conn = conn();

    try {
        $stmt = $conn->prepare("SELECT id_user, password_hash FROM users WHERE email = ?");
        $stmt->bind_param("s", $email);
        $stmt->execute();

        $password_hash = null;
        $user_id = null;

        $stmt->bind_result($user_id, $password_hash);
        $stmt->fetch();

        $stmt->close();
        $conn->close();

        if (password_verify($password, $password_hash)) {
            session_regenerate_id(true);
            $_SESSION["logged_in"] = true;
            $_SESSION["user_id"] = $user_id;
            return ["success"=>true, "message"=>"Successfully logged in"];
        } else {
            return ["success"=>false, "message"=>"Failed to log in"];
        }
    } catch (Exception $e) {
        if (isset($stmt)) $stmt->close();
        if (isset($conn)) $conn->close();
        return ["success" => false, "message" => "Log in failed"];
    }
}
?>

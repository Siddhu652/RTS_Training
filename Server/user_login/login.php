<?php


header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST, GET, PUT, DELETE');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
session_start();
include("../../db_connection/connection.php");


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!isset($_POST['username']) || !isset($_POST['password'])) {
        echo json_encode(["status" => "error", "message" => "Username or password not provided"]);
        exit;
    }

    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    $stmt = $pdo->prepare("SELECT * FROM user_details WHERE username = :username");
    $stmt->bindParam(':username', $username);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($user && password_verify($password, $user['password'])) {
        $_SESSION['username'] = $username;
        $_SESSION['role'] = $user['role'];
      

        if (!isset($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }

        $_SESSION['combined_token'] = $_SESSION['csrf_token'] . "$" . $_SESSION['username'] . "$" . $_SESSION['role'];

        echo json_encode(["status" => "success", "message" => "Login successful", "combined_token" => $_SESSION['combined_token']]);
    } else {
        echo json_encode(["status" => "error", "message" => "Invalid username or password"]);
    }
}
?>

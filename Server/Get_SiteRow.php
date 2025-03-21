<?php
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

include("../db_connection/connection.php");

try {
    if ($_SERVER['REQUEST_METHOD'] == 'GET') {

        if (!isset($_GET['id']) || empty($_GET['id'])) {
            echo json_encode(["status" => "error", "message" => "Missing 'id' in request"]);
            exit;
        }

        $id = $_GET['id']; 
        $stmt = $pdo->prepare("SELECT * FROM site_details WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $sites = $stmt->fetchAll(PDO::FETCH_ASSOC);

        if ($sites) {
            echo json_encode(["status" => "success", "data" => $sites], JSON_PRETTY_PRINT);
        } else {
            echo json_encode(["status" => "error", "message" => "No record found"]);
        }
    }
} catch (PDOException $e) {
    echo json_encode(["status" => "error", "error_message" => $e->getMessage()]);
}
?>

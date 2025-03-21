<?php
require_once 'config.php'; // Securely load API Key

header("Content-Type: application/json");
echo json_encode(["api_key" => GOOGLE_MAPS_API_KEY]);
?>

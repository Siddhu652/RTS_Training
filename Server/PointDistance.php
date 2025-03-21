<?php
header("Content-Type: application/json");
include("../db_connection/connection.php");

if (!isset($_POST['lat1'], $_POST['lng1'], $_POST['lat2'], $_POST['lng2'])) {
    echo json_encode(["error" => "Invalid input. Missing coordinates."]);
    exit;
}

$lat1 = floatval($_POST['lat1']);
$lng1 = floatval($_POST['lng1']);
$lat2 = floatval($_POST['lat2']);
$lng2 = floatval($_POST['lng2']);

// Haversine Formula Function
function haversineDistance($lat1, $lng1, $lat2, $lng2) {
    $earthRadius = 6371000; 
    $lat1 = deg2rad($lat1);
    $lng1 = deg2rad($lng1);
    $lat2 = deg2rad($lat2);
    $lng2 = deg2rad($lng2);

    $deltaLat = $lat2 - $lat1;
    $deltaLng = $lng2 - $lng1;

    $a = sin($deltaLat / 2) * sin($deltaLat / 2) +
         cos($lat1) * cos($lat2) *
         sin($deltaLng / 2) * sin($deltaLng / 2);

    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));

    return $earthRadius * $c;
}

$distance = haversineDistance($lat1, $lng1, $lat2, $lng2);
$distance_km = round($distance / 1000, 2); // Convert to KM

$stmt = $pdo->prepare("INSERT INTO distance (distance_km) VALUES (?)");
$inserted = $stmt->execute([$distance_km]);

if ($inserted) {
    echo json_encode(["success" => "Distance saved", "distance_km" => $distance_km, "distance_meters" => round($distance, 2)]);
} else {
    echo json_encode(["error" => "Failed to save distance"]);
}
?>

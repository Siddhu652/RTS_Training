<?php
include("../db_connection/connection.php");

$stmt = $pdo->query("SELECT route_id, lat, lng, speed FROM routes ORDER BY route_id, lat DESC");
$values = $stmt->fetchAll(PDO::FETCH_ASSOC);

$routes = [];
foreach ($values as $row) {
    $route_id = $row['route_id'];
    
    if (!isset($routes[$route_id])) {
        $routes[$route_id] = [
            "path" => [],
            "color" => "#FF0000"
        ];
    }
    
    $routes[$route_id]["path"][] = [
        "lat" => floatval($row['lat']),
        "lng" => floatval($row['lng']),
        "speed" => intval($row['speed'])
    ];
}

echo json_encode(["routes" => array_values($routes)]);


?>

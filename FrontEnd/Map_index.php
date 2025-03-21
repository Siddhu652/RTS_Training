<?php

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        die("CSRF token validation failed!");
    }
  }

require "../config.php";

  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" 
    integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <style>
        *{
            margin:0px;
            padding:0px;
            font-size:15px;
        }
        #map{
            height:400px;
            width: 100%;
        }

        .popup-container {
            position: absolute;
            background: white;
            padding: 8px;
            border-radius: 5px;
            box-shadow: 0px 0px 5px rgba(0,0,0,0.3);
            font-size: 14px;
            display: none;
            z-index: 1000;
        }
    </style>
</head>

<body>

<script src="Map_index.js"></script>




<body>
<div class="container my-4">
    <div class="row justify-content-center">
        <div class="col-md-6 bg-light p-4 rounded shadow">
            <h4 class="text-center text-primary mb-4">Map Distance</h4>
            <form action="Map.php" method="post">

         
<!-- circle  -->
                <div id="circleFields">
        <div class="form-group">
            <label for="latitude">LATITUDE</label>
            <input type="text" class="form-control" id="latitude" name="latitude" placeholder="Enter Latitude">
        </div>

        <div class="form-group">
            <label for="longitude">LONGITUDE</label>
            <input type="text" class="form-control" id="longitude" name="longitude" placeholder="Enter Longitude">
        </div>

        <div class="form-group">
            <label for="radius">RADIUS</label>
            <input type="text" class="form-control" id="radius" name="radius" placeholder="Enter Radius">
        </div>
    </div>

         <button type="button" class="btn btn-success mt-3" onclick="getLatLngValue()">SUBMIT</button>
         </form>
        </div>
    </div>
</div>


    <div id="map" class="rounded shadow-lg"></div>

    <button type="button" class="btn btn-success mt-3" onclick="drawPolyLine()">DRAW POLYLINE</button>
    <script async defer src="https://maps.googleapis.com/maps/api/js?key=<?=GOOGLE_MAPS_API_KEY?>&callback=initMap">
    </script>

</body>
</html>
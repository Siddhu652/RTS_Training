

// Example call
// getLocationData(11.281123, 77.598758);

let map, infoWindow, latLngClickValue, poly, i, distance, lat1, lat2, FinalDistance,firstpoint, lastpoint;


async function initMap() {
    const { Map } = await google.maps.importLibrary("maps");

    var location = { lat: 11.281123340902726, lng: 77.59875792698459 };

    map = new google.maps.Map(document.getElementById("map"), {
        zoom: 7,
        center: location
    });
    fetchRouteData();
/*draw polyline using the click event*/
    window.drawPolyLine = function () {
        poly = new google.maps.Polyline({
            strokeColor: "#000000",
            strokeOpacity: 1.0,
            strokeWeight: 3,
        });
        poly.setMap(map);

        map.addListener("click", addLatLng);
    };
// let startpoint = Startingpoint();
    Startingpoint();


function addLatLng(event) {
  const path = poly.getPath();

  path.push(event.latLng);
  new google.maps.Marker({
    position: event.latLng,
    title: "#" + path.getLength(),  
    map: map,
  });
}



    infoWindow = new google.maps.InfoWindow({
        content: "click somewhere",
        position: location,
    });

    infoWindow.open(map);

    map.addListener("click", (mapsMouseEvent) => {
        infoWindow.close();

        latLngClickValue = mapsMouseEvent.latLng.toJSON(); 

        document.getElementById("latitude").value = latLngClickValue.lat;
        document.getElementById("longitude").value = latLngClickValue.lng;

        console.log("Location selected:", latLngClickValue.lat, latLngClickValue.lng);

        infoWindow = new google.maps.InfoWindow({
            position: latLngClickValue,
        });

        infoWindow.setContent(`Latitude: ${latLngClickValue.lat}, Longitude: ${latLngClickValue.lng}`);
        infoWindow.open(map);
    });

    window.initMap = initMap;

}


   
function fetchRouteData() {
    $.ajax({
        url: "Route.JSON",
        type: "GET",
        dataType: "json",
        success: function(response) {
console.log("response :",response);

            if (response.routes) {
                response.routes.forEach(function(route) {
                  new google.maps.Polyline({
                        path: route.path,
                        geodesic: true,
                        strokeColor: route.color || "#FF0000", // Default color if not provided
                        strokeOpacity: 1.0,
                        strokeWeight: 4,
                        map: map
                    });
                });
            } else {
                console.error("Invalid JSON structure in Route.JSON");
            }
        },
        error: function(xhr, status, error) {
            console.error("Error fetching route data:", error);
        }
    });
}

function getLatLngValue() {
    if (latLngClickValue) {
        console.log("Latitude:", latLngClickValue.lat, "Longitude:", latLngClickValue.lng);
        let radius = document.getElementById("radius").value;
        drawCircle(
        parseFloat(latLngClickValue.lat),
        parseFloat(latLngClickValue.lng),
        parseFloat(radius)
    );
        return latLngClickValue;
    } else {
        console.log("No location selected yet.");
    }
}





//function for drawing circle
     function drawCircle(lat, lng, radius) {
    const circle = new google.maps.Circle({
        strokeColor: "#FF0000",
        strokeOpacity: 0.8,
        strokeWeight: 2,
        fillColor: "#FF0000",
        fillOpacity: 0.35,
        map,
        center: { lat: lat, lng: lng },
        radius: radius * 1000
    });

    let loc_position = {"lat": lat, "lng": lng};

 
  const infoWindow = new google.maps.InfoWindow({
        content: `<b>Circle Info:</b> ${lat, lng}`,
        position: loc_position
    });

    google.maps.event.addListener(circle, "mouseover", function () {
        infoWindow.open(map);
    });

    google.maps.event.addListener(circle, "mouseout", function () {
        infoWindow.close();
    });


    }
 

    function Startingpoint() {
         firstpoint, lastpoint;
    $.ajax({
        url: "../Server/FetchMapData.php",
        type: "GET",
        dataType: "json",
        success: function(response) {
            console.log("Response:", response); 
            if (response.routes) {
    for (let i = 0; i < response.routes.length; i++) {
        
        let route = response.routes[i];
         firstpoint = route.path[0];
        let lp = route.path.length - 1;
        lastpoint = route.path[lp];

       
        console.log("firstpoint :", firstpoint);

        console.log("lastpoint :", lastpoint);

        for (let j = 0; j < lp; j++) {
            let startPoint = route.path[j];   
            let endPoint = route.path[j + 1]; 
            let speed = startPoint.speed;   
            console.log(speed);
            
            let color = getColorBasedOnSpeed(speed); 
           new google.maps.Polyline({
                path: [startPoint, endPoint],
                geodesic: true,
                strokeColor: color, // Apply speed color
                strokeOpacity: 1.0,
                strokeWeight: 4,
                map: map
            });
        }

calculateDistance(firstpoint, lastpoint);

        // Place markers at the middle points
        for (let j = 1; j < lp; j++) {
            let middlePoint = route.path[j];

            new google.maps.Marker({
                position: middlePoint,
                map: map,
                icon: {
                    url: "https://maps.google.com/mapfiles/ms/icons/blue-dot.png",
                    scaledSize: new google.maps.Size(40, 40)
                }
            });
        }
                
    }
} else {
    console.error("Invalid JSON structure in response");
}

},
        error: function(xhr, status, error) {
            console.error("Error fetching route data:", error);
        }
    
    });
}

function calculateDistance(firstpoint, lastpoint) {
    $.ajax({
            url: "../Server/pointDistance.php",
            type: "POST",
            data: {
                lat1: firstpoint.lat,
                lng1: firstpoint.lng,
                lat2: lastpoint.lat,
                lng2: lastpoint.lng
            },
        dataType: "json",
    success: function(response) {
                if (response.success) {
                    console.log("Distance:", response.distance_km, "km");
                    console.log("reponse data" + JSON.stringify(response));
                    
                    getData(response.distance_km, firstpoint, lastpoint);
                } else {
                    console.error("Error:", response.error);
                }
        },
        error: function(xhr, status, error) {
         console.error("Error calculating distance:", error);
            }
        });
    }


    function getData(responseData, firstpoint, lastpoint){
        console.log("Final Distance:", responseData, "km");

    const startingicon = {
        url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRrPwL9QrYr8iQ4TwdUc5WGoAj9JeQD4f_LOg&s",
        scaledSize: new google.maps.Size(60, 60)
    };

    const endingicon = {
        url: "https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcQ1ZC0y59AEGM9ZhtzL3XAC91CXAYA11vrUeg&s",
        scaledSize: new google.maps.Size(60, 60)
    };

    let startMarker = new google.maps.Marker({
        position: firstpoint,
        map,
        icon: startingicon,
    });

    let endMarker = new google.maps.Marker({
        position: lastpoint,
        map,
        icon: endingicon,
    });

    let distanceInfoWindow = new google.maps.InfoWindow({
        content: `Distance: ${responseData} meters`
    });

    google.maps.event.addListener(startMarker, 'click', function () {
     distanceInfoWindow.setContent(`<b>Start Point</b><br>Distance: ${responseData} km`);
    distanceInfoWindow.open(map, startMarker);
                    });

    google.maps.event.addListener(endMarker, 'click', function () {
            distanceInfoWindow.setContent(`<b>End Point</b><br>Distance: ${responseData} km`);
        distanceInfoWindow.open(map, endMarker);
                    });
                }



function getColorBasedOnSpeed(speed) {
    if (speed <= 40) {
        return "#000000"; 
    } else if (speed>=40 && speed <= 50) {
        return "#FFFF00"; 
    } else {
        return "#FF0000"; 
    }
}

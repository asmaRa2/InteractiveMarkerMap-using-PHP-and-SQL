<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interactive Map with Markers</title>

    <!-- Leaflet.js CSS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />

    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@500&display=swap');

        * {
            margin: 0;
            padding: 0;
            font-family: 'Poppins', sans-serif;
        }

        body {
            background-image: linear-gradient(to right, #243949 0%, #517fa4 100%);
            background-repeat: no-repeat;
            background-attachment: fixed;
            background-size: cover;
        }

        .main {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .container {
            display: flex;
            justify-content: space-between;
            width: 1300px;
            height: 670px;
            padding: 30px;
            border-radius: 10px;
            background-color: #ffc0cb;
            box-shadow: rgba(0, 0, 0, 0.35) 0px 5px 15px;
        }

        .map-container {
            width: 70%;
        }
            
        .map {
            width: 100%;
            height: 100%;
        }

        .marker-container {
            position: relative;
            height: 100%;
            width: 28%;
        }

        .marker-container > h1 {
            text-align: center;
            margin: 10px;
        }

        .marker {
            display: flex;
            flex-direction: column;
            align-items: center;
            margin: 10px 10px;
            height: 230px;
        }

        .input-group {
            display: flex;
            align-items: center;
            justify-content: space-between;
            width: 95%;
            margin: 10px;
        }

        .input-group > input {
            padding: 5px;
            outline: none;
            font-size: 17px;
            width: 170px;
        }

        .marker > button {
            width: 95%;
            padding: 5px;
            font-size: 17px;
            margin: 10px;
            cursor: pointer;
            border-radius: 5px;
            border: none;
            box-shadow: rgba(99, 99, 99, 0.2) 0px 2px 8px 0px;
            background-color: rgb(255, 255, 255);
        }

        .marker > button:hover {
            background-color: rgb(220, 220, 220);
        }

        .marked > h2 {
            text-align: center;
            margin-top: 10px;
        }

        #markedLocations {
            overflow-y: auto;
            height: 300px;
            list-style: none;
        }

        #markedLocations > li {
            display: flex;
            justify-content: center;
            align-items: center;
        }

        #markedLocations > li div {
            display: flex;
            justify-content: space-between;
            background-color: rgba(255, 255, 255, 0.5);
            width: 90%;
            margin: 10px;
        }

        #markedLocations > li div button {
            font-size: 16px;
            color: rgb(0, 0, 255);
            text-decoration: underline;
            background-color: transparent;
            border: none;
            cursor: pointer;
        }

        #markedLocations > li div button:hover {
            color: rgb(88, 88, 255);
            text-decoration: none;
        }

        #markedLocations > li > button {
            background-color: transparent;
            border: none;
            outline: none;
            font-size: 17px;
            margin-left: 20px;
            color: rgb(220, 20, 20);
            cursor: pointer;
            width: 20px;
        }

        #markedLocations > li > button:hover {
            background-color: rgb(220, 200, 200);
            color: rgb(240, 10, 10);
        }
    </style>
</head>
<body>
    <div class="main">
        <div class="container">
            <div class="marker-container">  
            <h1>Marker Area</h1>
                <hr>
                <form class="marker" action="./endpoint/add-mark.php" method="POST">
                    <div class="input-group">
                        <h3>Marker Name:</h3>
                        <input id="markerName" type="text" name="mark_name">
                    </div>
                    <div class="input-group">
                        <h3>Latitude:</h3>
                        <input id="latitude" type="text" name="mark_lat" readonly>
                    </div>
                    <div class="input-group">
                        <h3>Longitude:</h3>
                        <input id="longitude" type="text" name="mark_long" readonly>
                    </div>
                    <button type="submit" id="saveMarker">Save Marker</button>
                </form>
                <hr>
                <div class="marked">
                    <h2>Marked Locations</h2>
                    <ul id="markedLocations">
                        <?php 
                            include('./conn/conn.php');

                            $stmt = $conn->prepare("SELECT * FROM tbl_mark");
                            $stmt->execute();

                            $result = $stmt->fetchAll();

                            foreach($result as $row) {
                                $markId = $row['tbl_mark_id'];
                                $markName = $row['mark_name'];
                                $markLat = $row['mark_lat'];
                                $markLong = $row['mark_long'];

                                ?>
                                <li id="<?= $markId ?>"><div><?= $markName ?><button onclick="viewLocation('<?= $markName ?>', <?= $markLat ?>, <?= $markLong ?>)">View Location</button></div><button onclick="deleteMark(<?= $markId ?>)">x</button></li>
                                <?php
                            }

                        ?>
                    </ul>
                </div>
            </div>
            <div class="map-container">
                <div class="map" id="map"></div>
            </div>
        </div>
    </div>

    <!-- Leaflet.js JS -->
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>

    <script>
        var map = L.map('map').setView([12.8797, 121.7740], 6);

        L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png', {    
            attribution: '&copy; <a href="http://osm.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        // Update marked locations when page loads
        <?php 
            include('./conn/conn.php');

            $stmt = $conn->prepare("SELECT * FROM tbl_mark");
            $stmt->execute();

            $result = $stmt->fetchAll();

            foreach($result as $row) {
                $markName = $row['mark_name'];
                $markLat = $row['mark_lat'];
                $markLong = $row['mark_long'];

                echo "updateMarkedLocations('$markName', $markLat, $markLong);";
            }
        ?>

        var markers = [];

        map.on('click', function(e) {
            var coordinates = e.latlng;
            
            document.getElementById('latitude').value = coordinates.lat.toFixed(6);
            document.getElementById('longitude').value = coordinates.lng.toFixed(6);
        });

        function updateMarkedLocations(name, lat, long) {
            var marker = L.marker([lat, long]).addTo(map);
            if (markers === undefined) {
                markers = [marker];
            } else {
                markers.push(marker);
            }
        }

        function viewLocation(name, lat, lng) {
            var marker = L.marker([lat, lng]).addTo(map);
            marker.bindPopup(name).openPopup();
            map.panTo(new L.LatLng(lat, lng));
        }

        function deleteMark(id) {
            if (confirm("Do you want to delete this mark?")) {
                window.location = "./endpoint/delete-mark.php?mark=" + id;
            }
        }
    </script>
</body>
</html>

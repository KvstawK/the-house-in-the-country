<?php
$marker_id = 2143;
$marker_url = wp_get_attachment_url($marker_id);
echo '
<!DOCTYPE html>
<html>
  <head>
    <title>OpenLayers Simple Map</title>
    <link rel="stylesheet" href="https://openlayers.org/en/v6.9.0/css/ol.css" type="text/css">
    <style>
      .map {
        height: 400px;
        width: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map" class="map"></div>
    <script src="https://cdn.jsdelivr.net/gh/openlayers/openlayers.github.io@master/en/v6.9.0/build/ol.js"></script>
    <script type="text/javascript">
      var markerUrl = "' . $marker_url . '";
console.log(markerUrl)
      var map = new ol.Map({
        target: "map",
        layers: [
          new ol.layer.Tile({
            source: new ol.source.OSM()
          })
        ],
        view: new ol.View({
          center: ol.proj.fromLonLat([24.9983580, 35.2245333]),
          zoom: 15
        })
      });

      var iconStyle = new ol.style.Style({
        image: new ol.style.Icon({
          anchor: [0.5, 1],
          anchorXUnits: "fraction",
          anchorYUnits: "fraction",
          src: markerUrl,
          scale: [1, 0.9], // Adjust the scale as needed for the marker
        })
      });

      var layer = new ol.layer.Vector({
        source: new ol.source.Vector({
          features: [
            new ol.Feature({
              geometry: new ol.geom.Point(ol.proj.fromLonLat([24.9983580, 35.2245333])),
              style: iconStyle
            })
          ]
        })
      });
      map.addLayer(layer);

      // Handle click event on map
      map.on("click", function (evt) {
        var feature = map.forEachFeatureAtPixel(evt.pixel, function (feature, layer) {
          return feature;
        });
        if (feature) {
          // Open multiple navigation options
          if (confirm("Open in Google Maps?")) {
            window.open("https://www.google.com/maps?daddr=35.2245333,24.9983580", "_blank");
          } else if (confirm("Open in Apple Maps?")) {
            window.open("http://maps.apple.com/?daddr=35.2245333,24.9983580", "_blank");
          } else if (confirm("Open in OsmAnd?")) {
            window.open("osmandmaps://navigate?lat=35.2245333&lon=24.9983580&z=15", "_blank");
          } else if (confirm("Open in HERE WeGo?")) {
            window.open("https://wego.here.com/directions/mix//35.2245333,24.9983580", "_blank");
          }
        }
      });
    </script>
  </body>
</html>
';
?>

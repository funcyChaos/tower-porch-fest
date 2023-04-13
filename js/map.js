// Initialization of the map
function initMap() {
  zoom = 14.2;
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 36.7650533, lng: -119.7995578 },
    zoom,
    mapId: '4049b264513558e3',
    minZoom: zoom - 2,
    maxZoom: zoom + 3,
  });

  let infoWindows = [];
  let markers = [];
  let openInfoWindow;

  // Fetch for all of the porches
  fetch(`${wpVars.homeURL}/wp-json/wp/v2/porch?_embed&per_page=100`)
    .then((res) => res.json())
    .then((data) => {
      data.map((porch) => {
        // Initializing the variable that will contain the porch information
        const porchName = porch.title.rendered;
        const address = porch.acf.porch_address;
        const porchStartTime = porch.acf.porch_start_time;
        const porchEndTime = porch.acf.porch_end_time;
        const porchType = porch.acf.host_type;
        const porchDesc = porch.content.rendered;
        const porchPageURL = porch.link;
        const hasInfoBooth = porch.acf.has_info_booth;
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);
        // Marker color is set based upon porch information
        if (porchType === 'Sponsored Porch') {
          markerColor = '#208f95';
        } else if (porchType === 'Porta Potty') {
          markerColor = '#1363DF';
        } else if (hasInfoBooth === 'Yes') {
          markerColor = '#F45050';
        } else {
          markerColor = '#462d62';
        }

        // If porch has a featured image it will use that, if not it will default to the porch fest logo
        if (porch._embedded) {
          porchImage = porch._embedded['wp:featuredmedia'][0].source_url;
        } else {
          porchImage = wpVars.defaultImageURL;
        }

        // Construction of the GET DIRECTIONS button
        const directionsBtn = document.createElement('button');
        directionsBtn.id = 'direct-btn';
        directionsBtn.type = 'button';
        directionsBtn.textContent = 'GET DIRECTIONS';

        // Construction of the CONTENT div responsible for populating info window
        const contentDiv = document.createElement('div');
        contentDiv.id = 'content';

        // Contruction of button container to append button to
        const buttonContainer = document.createElement('div');
        buttonContainer.id = 'btn-container';

        // The div that contains the information that populates the info window
        if (porchType === 'Porta Potty') {
          contentString =
            `<img src=${porchImage} alt="porch" />` +
            '<div id="content-header">' +
            `<h3>${porchName}</h3>` +
            `<p>${address}</p>` +
            '</div>' +
            '<div id="desc">' +
            `${porchDesc}` +
            '</div>';
        } else {
          contentString =
            `<img src=${porchImage} alt="porch" />` +
            '<div id="content-header">' +
            `<h3>${porchName}</h3>` +
            `<p>${address}</p>` +
            '</div>' +
            '<div id="desc">' +
            `${porchDesc}` +
            '</div>' +
            '<div id="lineup-info">' +
            `<p>${porchStartTime} - ${porchEndTime}</p>` +
            `<a href='${porchPageURL}' target="_blank">SEE LINEUP</a>` +
            '</div>';
        }

        buttonContainer.appendChild(directionsBtn);
        contentDiv.innerHTML = contentString;
        contentDiv.appendChild(buttonContainer);

        // Event listener to trigger the directions/routing
        directionsBtn.addEventListener('click', () => {
          // Requests the users location
          if (window.navigator.geolocation) {
            window.navigator.geolocation.getCurrentPosition(
              locationSuccess,
              locationRejected
            );
          }
          // Resquests directions if users location is obtained
          function locationSuccess(position) {
            const directions = new google.maps.DirectionsService();
            // Requests route
            directions.route(
              {
                origin: {
                  lat: position.coords.latitude,
                  lng: position.coords.longitude,
                },
                destination: `${address}`,
                provideRouteAlternatives: false,
                travelMode: 'WALKING',
                unitSystem: google.maps.UnitSystem.IMPERIAL,
              },
              (response, status) => {
                if (status === 'OK') {
                  // Renders route on map
                  new google.maps.DirectionsRenderer({
                    suppressMarkers: true,
                    directions: response,
                    map: map,
                  });
                }
                console.log(response);
                console.log(status);
              }
            );
          }
          // Handles functionality if users location can not be requested
          function locationRejected(error) {
            console.log(error);
          }
        });
        // Creates custom marker
        const svgMarker = {
          path: 'M 0 0 q 2.906 0 4.945 2.039 t 2.039 4.945 q 0 1.453 -0.727 3.328 t -1.758 3.516 t -2.039 3.07 t -1.711 2.273 l -0.75 0.797 q -0.281 -0.328 -0.75 -0.867 t -1.688 -2.156 t -2.133 -3.141 t -1.664 -3.445 t -0.75 -3.375 q 0 -2.906 2.039 -4.945 t 4.945 -2.039 z',
          fillColor: markerColor,
          fillOpacity: 0.9,
          strokeWeight: 1,
          rotation: 0,
          scale: 2,
          anchor: new google.maps.Point(0, 20),
        };

        // Places marker on the map for each porch
        const marker = new google.maps.Marker({
          position: { lat, lng },
          map,
          icon: svgMarker,
        });

        // Creates a info window for each marker
        const infoWindow = new google.maps.InfoWindow({
          content: contentDiv,
        });

        infoWindows.push(infoWindow);
        markers.push(marker);

        //end of loop
      });

      // Handles the open/close functionality of info window when clicking on other markers
      for (let i = 0; i < markers.length; i++) {
        markers[i].addListener('click', () => {
          if (openInfoWindow && openInfoWindow != infoWindows[i]) {
            openInfoWindow.close();
          }
          openInfoWindow = infoWindows[i];
          infoWindows[i].open({
            anchor: markers[i],
            map,
          });
        });
      }

      // Closes info window when map is clicked
      map.addListener('click', function () {
        if (openInfoWindow) openInfoWindow.close();
      });
    });
}

window.initMap = initMap;

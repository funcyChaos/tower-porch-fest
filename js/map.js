// Function responsible for grabbing the users location (permissions from the user is required)
function getLocation() {
  if (window.navigator.geolocation) {
    window.navigator.geolocation.getCurrentPosition(console.log, console.log);
  }
}

// getLocation();

// Initialization of the map
function initMap() {
  zoom = 14.5;
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
        const porchDesc = porch.content.rendered;
        const porchPageURL = porch.link;
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);

        // If porch has a featured image it will use that, if not it will default to the porch fest logo
        if (porch._embedded) {
          porchImage = porch._embedded['wp:featuredmedia'][0].source_url;
        } else {
          porchImage =
            'http://towerporchfest.local/wp-content/uploads/2023/04/Rectangle-1.jpg';
        }

        // If porch has performer info it will use that, if not it will default to empty string
        if (porch.acf.performer_1_start_time) {
          startTime = porch.acf.performer_1_start_time + ' AM';
        } else {
          startTime = ' ';
        }

        // Same logic that is used for start time
        if (porch.acf.performer_5_end_time) {
          return (endTime = parseInt(porch.acf.performer_5_end_time) % 12);
        }

        const directionsBtn = document.createElement('button');
        directionsBtn.id = 'direct-btn';
        directionsBtn.type = 'button';
        directionsBtn.textContent = 'GET DIRECTIONS';

        const contentDiv = document.createElement('div');
        contentDiv.id = 'content';

        const buttonContainer = document.createElement('div');
        buttonContainer.id = 'btn-container';

        // The div that contains the information that populates the info window
        const contentString =
          `<img src=${porchImage} alt="porch" />` +
          '<div id="content-header">' +
          `<h3>${porchName}</h3>` +
          `<p>${address}</p>` +
          '</div>' +
          '<div id="desc">' +
          `${porchDesc}` +
          '</div>' +
          '<div id="lineup-info">' +
          `<p>${startTime} - ${endTime}PM </p>` +
          `<a href='${porchPageURL}' target="_blank">SEE LINEUP</a>` +
          '</div>';

        buttonContainer.appendChild(directionsBtn);
        contentDiv.innerHTML = contentString;
        contentDiv.appendChild(buttonContainer);

        // Event listener to trigger the directions/routing
        directionsBtn.addEventListener('click', () => {
          const directions = new google.maps.DirectionsService();
          directions.route(
            {
              origin: '815 E Olive Ave, Fresno, CA 93728',
              destination: `${address}`,
              provideRouteAlternatives: false,
              travelMode: 'WALKING',
              drivingOptions: {
                departureTime: new Date(),
                trafficModel: 'pessimistic',
              },
              unitSystem: google.maps.UnitSystem.IMPERIAL,
            },
            (response, status) => {
              if (status === 'OK') {
                new google.maps.DirectionsRenderer({
                  suppressMarkers: true,
                  directions: response,
                  map: map,
                });
              }
              // console.log(response);
              // console.log(status);
            }
          );
        });

        // Places a marker on the map for each porch
        const marker = new google.maps.Marker({
          position: { lat, lng },
          map,
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

function getLocation() {
  if (window.navigator.geolocation) {
    window.navigator.geolocation.getCurrentPosition(console.log, console.log);
  }
}

// getLocation();

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

  // The marker, positioned at coordinates
  fetch(`${wpVars.homeURL}/wp-json/wp/v2/porch?per_page=100`)
    .then((res) => res.json())
    .then((data) => {
      data.map((porch) => {
        // console.log(porch);
        const porchID = porch.id;
        const porchImage = porch.acf.porch_logo;
        const porchName = porch.title.rendered;
        const address = porch.acf.porch_address;
        const porchDesc = porch.content.rendered;
        const vendorOnSite = porch.acf.food_vendor_on_site;
        const startTime = porch.acf.performer_1_start_time;
        if (porch.acf.performer_5_end_time) {
          return (endTime = parseInt(porch.acf.performer_5_end_time) % 12);
        }
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);

        const directionsBtn = document.createElement('button');
        directionsBtn.id = 'direct-btn';
        directionsBtn.type = 'button';
        directionsBtn.textContent = 'GET DIRECTIONS';

        const contentDiv = document.createElement('div');
        contentDiv.id = 'content';

        const buttonContainer = document.createElement('div');
        buttonContainer.id = 'btn-container';

        const contentString =
          `<img src="https://images.unsplash.com/photo-1631458325834-8f678e48912c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8" alt="porch" />` +
          '<div id="content-header">' +
          `<h3>${porchName}</h3>` +
          `<p>${address}</p>` +
          '</div>' +
          '<div id="desc">' +
          `${porchDesc}` +
          '</div>' +
          '<div id="lineup-info">' +
          `<p>11AM - ${endTime}PM </p>` +
          `<a href='/'>SEE LINEUP</a>` +
          '</div>';

        buttonContainer.appendChild(directionsBtn);
        contentDiv.innerHTML = contentString;
        contentDiv.appendChild(buttonContainer);

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

        const marker = new google.maps.Marker({
          position: { lat, lng },
          map,
        });

        const infoWindow = new google.maps.InfoWindow({
          content: contentDiv,
        });

        infoWindows.push(infoWindow);
        markers.push(marker);

        //end of loop
      });

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
      map.addListener('click', function () {
        if (openInfoWindow) openInfoWindow.close();
      });
    });
}

window.initMap = initMap;

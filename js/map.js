function getPorches() {
  fetch('http://towerporchfest.local/wp-json/wp/v2/porch?per_page=100')
    .then((res) => res.json())
    .then((data) => {
      let interval = 1000;

      data.map((porch) => {
        setCoords(porch.id, porch.acf.porch_address, interval);
        interval += 1000;
      });
    });
}

function setCoords(id, address, interval) {
  setTimeout(() => {
    fetch(
      `https://maps.googleapis.com/maps/api/geocode/json?address=${address}&key=`
    )
      .then((res) => res.json())
      .then((data) => {
        console.log(data.results[0].geometry.location);
        const lon = data.results[0].geometry.location.lng;
        const lat = data.results[0].geometry.location.lat;

        fetch('/wp-json/porches/v1/adds', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/JSON',
          },
          body: JSON.stringify({
            id,
            lon,
            lat,
          }),
        }).then((res) => res.json());
        // .then((data) => console.log(data));
      });
  }, interval);
}

// getPorches();

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
      let counter = 0;

      data.map((porch) => {
        console.log(porch);
        const porchID = porch.id;
        const porchName = porch.title.rendered;
        const address = porch.acf.porch_address;
        const vendorOnSite = porch.acf.food_vendor_on_site;
        const startTime = porch.acf.performer_1_start_time;
        // Need to update acf fields and change this to end time
        const endTime = porch.acf.performer_5_end_time;
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);

        const directionsBtn = document.createElement('button');
        directionsBtn.id = 'direct-btn';
        directionsBtn.type = 'button';
        directionsBtn.textContent = 'GET DIRECTIONS';

        const contentDiv = document.createElement('div');
        contentDiv.id = `content`;

        const buttonContainer = document.createElement('div');
        buttonContainer.id = 'btn-container';

        const contentString =
          '<img src="https://images.unsplash.com/photo-1631458325834-8f678e48912c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8" alt="porch" />' +
          '<div id="content-header">' +
          `<h3>${porchName}</h3>` +
          `<p>${address}</p>` +
          '</div>' +
          '<div id="desc">' +
          '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.</p>' +
          '</div>' +
          '<div id="lineup-info">' +
          `<p>${startTime} - 2PM</p>` +
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

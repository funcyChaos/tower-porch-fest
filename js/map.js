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

function initMap() {
  zoom = 14.5;
  map = new google.maps.Map(document.getElementById('map'), {
    center: { lat: 36.7650533, lng: -119.7995578 },
    zoom,
    mapId: '4049b264513558e3',
    minZoom: zoom - 2,
    maxZoom: zoom + 3,
    // restriction: {
    //   latLngBounds: {
    //     north: 37.9176,
    //     south: 37.663568,
    //     east: -121.902542,
    //     west: -122.515719,
    //   },
    // },
  });

  // The marker, positioned at coordinates
  fetch('http://towerporchfest.local/wp-json/wp/v2/porch?per_page=100')
    .then((res) => res.json())
    .then((data) => {
      data.map((porch) => {
        // console.log(porch);
        const porchName = porch.title.rendered;
        const address = porch.acf.porch_address;
        const startTime = porch.acf.performer_1_start_time;
        // Need to update acf fields and change this to end time
        const endTime = porch.acf.performer_5_start_time;
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);

        const contentString =
          '<div id="content">' +
          '<img src="https://images.unsplash.com/photo-1631458325834-8f678e48912c?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8" alt="porch" />' +
          '<div id="content-header">' +
          `<h3>${porchName}</h3>` +
          `<p>${address}</p>` +
          '</div>' +
          '<div id="desc">' +
          '<p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industrys standard dummy text ever since the 1500s.</p>' +
          '</div>' +
          '<div id="lineup-info">' +
          `<p>11AM - 2PM</p>` +
          '<a href="/">SEE LINEUP</a>' +
          '</div>' +
          '<div id="directions-btn">' +
          '<button type="button">GET DIRECTIONS</button>' +
          '</div>';
        ('</div>');

        const marker = new google.maps.Marker({
          position: { lat, lng },
          map,
        });

        const infoWindow = new google.maps.InfoWindow({
          content: contentString,
          ariaLabel: 'Uluru',
        });

        marker.addListener('click', () => {
          infoWindow.open({
            anchor: marker,
            map,
          });
        });
      });
    });
}

window.initMap = initMap;

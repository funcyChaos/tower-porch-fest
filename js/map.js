porches = [];

function getPorches() {
  fetch('http://towerporchfest.local/wp-json/wp/v2/porch')
    .then((res) => res.json())
    .then((porches) => {
      porches.map((porch) => {
        console.log(porch);
        fetch(`https://geocode.maps.co/search?q=${porch.address}`)
          .then((res) => res.json())
          .then((data) => console.log(data));
      });
    });
}

getPorches();
// console.log(porches);
// function getGeoCodes() {
//   PorchesArr.map((porch) => {
//     fetch(`https://geocode.maps.co/search?q=${porch.address}`)
//       .then((res) => res.json())
//       .then((data) => {
//         PorchesCoordsArr.push({
//           name: porch.name,
//           porch: porch.address,
//           address: [data[0].lat, data[0].lon],
//         });
//       });
//   });
//   // console.log(window.location.origin);
// }

// getGeoCodes();

// Initialize and add the map
async function initMap() {
  zoom = 15;
  // //Creation of the map container
  const map = new google.maps.Map(document.getElementById('map'), {
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
  // const marker = new google.maps.Marker({
  //   position: { lat: 36.7650533, lng: -119.7995578 },
  //   map: map,
  // });

  const contentString =
    '<div id="content">' +
    '<div id="siteNotice">' +
    '</div>' +
    '<h1 id="firstHeading" class="firstHeading">Uluru</h1>' +
    '<div id="bodyContent">' +
    '<p><b>Uluru</b>, also referred to as <b>Ayers Rock</b>, is a large ' +
    'sandstone rock formation in the southern part of the ' +
    'Northern Territory, central Australia. It lies 335&#160;km (208&#160;mi) ' +
    'south west of the nearest large town, Alice Springs; 450&#160;km ' +
    '(280&#160;mi) by road. Kata Tjuta and Uluru are the two major ' +
    'features of the Uluru - Kata Tjuta National Park. Uluru is ' +
    'sacred to the Pitjantjatjara and Yankunytjatjara, the ' +
    'Aboriginal people of the area. It has many springs, waterholes, ' +
    'rock caves and ancient paintings. Uluru is listed as a World ' +
    'Heritage Site.</p>' +
    '<p>Attribution: Uluru, <a href="https://en.wikipedia.org/w/index.php?title=Uluru&oldid=297882194">' +
    'https://en.wikipedia.org/w/index.php?title=Uluru</a> ' +
    '(last visited June 22, 2009).</p>' +
    '</div>' +
    '</div>';
  const infowindow = new google.maps.InfoWindow({
    content: contentString,
    ariaLabel: 'Uluru',
  });
  const marker = new google.maps.Marker({
    position: { lat: 36.7650533, lng: -119.7995578 },
    map,
    title: 'Uluru (Ayers Rock)',
  });

  marker.addListener('click', () => {
    infowindow.open({
      anchor: marker,
      map,
    });
  });
}
// let str = '1007 N Van Ness Ave, Fresno, CA';
// let lat;
// let lng;

// const res = await fetch(`https://geocode.maps.co/search?q=${str}`);
// const data = await res.json();
// console.log(data);
// lat = parseFloat(data[0].lat);
// lng = parseFloat(data[0].lon);

// PorchesCoordsArr.map((porch) => {
//   const marker = new google.maps.Marker({
//     position: {
//       lat: parseFloat(porch.address[0]),
//       lng: parseFloat(porch.address[1]),
//     },
//     map: map,
//   });
// });
// }

window.initMap = initMap;

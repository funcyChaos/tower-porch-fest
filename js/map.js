const PorchesArr = [
  {
    name: 'Benjamin Napolean',
    porch: 'Goldstiens Mortuary & Delicatessen',
    address: '1279 N Wishon Ave, Fresno, CA',
  },
  {
    name: 'Melissa Blake and Genevieve Hinojos-Spalding',
    porch: 'MB Sells Fresno & Home by Genevieve Hinojos-Spalding',
    address: '223 E University Ave, Fresno, CA',
  },
  {
    name: 'Paul Cruikshank',
    porch: 'Ragin Records',
    address: '1118 N Fulton St, Fresno, CA',
  },
  {
    name: 'Mike',
    porch: 'Spokeasy Public House',
    address: '1472 N Van Ness Ave, Fresno, CA',
  },
];

const PorchesCoordsArr = [];

function getPorches() {
  fetch('http://towerporchfest.local/wp-json/wp/v2/posts/51')
    .then((res) => res.json())
    .then((data) => console.log(data));
}

getPorches();

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
  // const mapContainer = document.createElement('div');
  // mapContainer.setAttribute('id', 'map');

  // const mapContainer = document.getElementById('map-container');
  // document.body.appendChild(mapContainer);
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
  const marker = new google.maps.Marker({
    position: { lat: 36.7650533, lng: -119.7995578 },
    map: map,
  });

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
}

window.initMap = initMap;

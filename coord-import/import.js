function getPorches() {
  fetch(`${wpVars.homeURL}/wp-json/wp/v2/porch?per_page=100`)
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

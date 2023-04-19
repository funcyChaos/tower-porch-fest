// Initialization of the map
function initMap() {
  const urlArgs = decodeURIComponent(window.location.search)
    .split('=')[1]
    .split(':')[0];
  // console.log(urlArgs);

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
      // console.log(getDateFromHours('01:12'));
      const currentDate = new Date();
      // console.log('current date', currentDate);
      // Will be users input
      let timeSelect = new Date(
        currentDate.getFullYear(),
        currentDate.getMonth(),
        currentDate.getDate(),
        ...[urlArgs, 0]
      );
      // Construction of the select element and its options
      const filterForm = document.createElement('form');
      filterForm.id = 'map-filter';

      const timeInput = document.createElement('input');
      timeInput.type = 'time';
      timeInput.id = 'time-input';
      timeInput.name = 'time-input';
      const timeInputLabel = document.createElement('label');
      timeInputLabel.htmlFor = 'time-input';
      timeInputLabel.appendChild(document.createTextNode('View by time'));
      timeInput.addEventListener('change', (e) => {
        timeSelect = e.target.value;
      });

      const hasFood = document.createElement('input');
      hasFood.type = 'checkbox';
      hasFood.name = 'Food';
      hasFood.value = 'Food';
      hasFood.id = 'has-food';
      const hasFoodLabel = document.createElement('label');
      hasFoodLabel.htmlFor = 'has-food';
      hasFoodLabel.appendChild(document.createTextNode('Food'));

      const hasPortaPotty = document.createElement('input');
      hasPortaPotty.type = 'checkbox';
      hasPortaPotty.name = 'Porta Potty';
      hasPortaPotty.value = 'Porta Potty';
      hasPortaPotty.id = 'has-porta-potty';
      const hasPortaPottyLabel = document.createElement('label');
      hasPortaPottyLabel.htmlFor = 'has-porta-potty';
      hasPortaPottyLabel.appendChild(document.createTextNode('Porta Potty'));

      const submitBtn = document.createElement('button');
      submitBtn.type = 'submit';
      submitBtn.id = 'submit-btn';
      submitBtn.innerText = 'Submit';
      filterForm.appendChild(timeInputLabel);
      filterForm.appendChild(timeInput);
      filterForm.appendChild(hasFoodLabel);
      filterForm.appendChild(hasFood);
      filterForm.appendChild(hasPortaPottyLabel);
      filterForm.appendChild(hasPortaPotty);
      filterForm.appendChild(submitBtn);
      document.getElementById('map').appendChild(filterForm);

      data.map((porch) => {
        // console.log(porch);
        function isPerformance(startTime) {
          startTime.includes('Performer') || startTime === ''
            ? null
            : startTimes.push(startTime);
        }

        const startTimes = [];

        isPerformance(porch.acf.performer_1_start_time);
        isPerformance(porch.acf.performer_2_start_time);
        isPerformance(porch.acf.performer_3_start_time);
        isPerformance(porch.acf.performer_4_start_time);
        isPerformance(porch.acf.performer_5_start_time);
        isPerformance(porch.acf.performer_6_start_time);
        isPerformance(porch.acf.performer_7_start_time);
        isPerformance(porch.acf.performer_8_start_time);

        let showPorch = false;
        startTimes.forEach((time) => {
          // console.log('Time', time);
          time = time.split(':');
          // if (time[0] < 11) {
          //   time[0] = parseInt(time[0]);
          //   time[0] += 12;
          //   // console.log(time);
          // }
          let now = new Date();
          const porchTime = new Date(
            now.getFullYear(),
            now.getMonth(),
            now.getDate(),
            ...time
          );
          if (showPorch) return;

          if (porchTime >= timeSelect) {
            console.log(porchTime, ':', timeSelect);
            showPorch = true;
            return;
          }
        });

        if (!showPorch) {
          // console.log(showPorch);
          return;
        }

        // Initializing the variable that will contain the porch information
        const porchName = porch.title.rendered;
        const address = porch.acf.porch_address;
        const porchStartTime = porch.acf.porch_start_time;
        const porchEndTime = porch.acf.porch_end_time;
        const porchType = porch.acf.host_type;
        const porchDesc = porch.content.rendered;
        const porchPageURL = porch.link;
        const hasInfoBooth = porch.acf.has_info_booth;
        const infoBooth = hasInfoBooth === 'Yes' ? true : false;
        const lat = Number(porch.acf.latitude);
        const lng = Number(porch.acf.longitude);
        // console.log(hasInfoBooth)
        // Marker color is set based upon porch information
        // console.log(infoBooth)
        if (hasInfoBooth === 'Yes') {
          markerColor = '#F45050';
        } else if (porchType === 'Sponsored Porch') {
          markerColor = '#208f95';
        } else if (porchType === 'Porta Potty') {
          markerColor = '#ffff00';
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
        const seeLineupBtn = document.createElement('a');
        seeLineupBtn.id = 'lineup-btn';
        seeLineupBtn.href = porchPageURL + '#band_lineup';
        seeLineupBtn.textContent = 'See Lineup';

        // Construction of the CONTENT div responsible for populating info window
        const contentDiv = document.createElement('div');
        contentDiv.id = 'content';

        // Contruction of button container to append button to
        const buttonContainer = document.createElement('div');
        buttonContainer.id = 'btn-container';

        const directionsBtn = document.createElement('button');
        directionsBtn.type = 'button';
        directionsBtn.id = 'directions-btn';
        directionsBtn.textContent = 'Get Directions';
        const porchTimes = document.createElement('p');
        porchTimes.innerText = `${porchStartTime} - ${porchEndTime}`;

        const lineupInfo = document.createElement('div');
        lineupInfo.id = 'lineup-info';
        lineupInfo.appendChild(porchTimes);
        lineupInfo.appendChild(directionsBtn);

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
            '</div>';
        }

        buttonContainer.appendChild(seeLineupBtn);
        contentDiv.innerHTML = contentString;
        contentDiv.appendChild(lineupInfo);
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
        let svgMarker;
        if (infoBooth) {
          svgMarker = {
            path: 'M 11.25 22.5 c -0.3538 0 -0.6813 -0.187 -0.861 -0.4915 l -1.0962 -1.8542 C 7.0418 16.349 4.916 12.755 4.1898 11.2973 c -0.528 -1.0828 -0.7937 -2.2388 -0.7937 -3.4432 C 3.396 3.5232 6.9192 0 11.25 0 c 4.3308 0 7.854 3.5232 7.854 7.854 c 0 1.2038 -0.2657 2.3595 -0.7893 3.4352 c -0.0063 0.013 -0.0132 0.026 -0.02 0.0387 c -0.7402 1.4772 -2.8525 5.0483 -5.0883 8.8272 l -1.0955 1.8533 C 11.9313 22.313 11.6037 22.5 11.25 22.5 z M 11.25 2.175 c -2.979 0 -5.4028 2.4235 -5.4028 5.4025 c 0 2.979 2.4238 5.4025 5.4028 5.4025 c 2.9787 0 5.4025 -2.4235 5.4025 -5.4025 C 16.6525 4.5985 14.2287 2.175 11.25 2.175 z M 11.25 11.717 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 V 7.3275 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 3.6395 C 12 11.3812 11.6642 11.717 11.25 11.717 z M 11.25 5.522 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 v -0.555 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 0.555 C 12 5.1863 11.6642 5.522 11.25 5.522 z',
            fillColor: markerColor,
            fillOpacity: 0.9,
            strokeWeight: 1,
            rotation: 0,
            scale: 2,
            anchor: new google.maps.Point(0, 20),
          };
        } else {
          // Creates custom marker
          svgMarker = {
            path: 'M 0 0 q 2.906 0 4.945 2.039 t 2.039 4.945 q 0 1.453 -0.727 3.328 t -1.758 3.516 t -2.039 3.07 t -1.711 2.273 l -0.75 0.797 q -0.281 -0.328 -0.75 -0.867 t -1.688 -2.156 t -2.133 -3.141 t -1.664 -3.445 t -0.75 -3.375 q 0 -2.906 2.039 -4.945 t 4.945 -2.039 z',
            fillColor: markerColor,
            fillOpacity: 0.9,
            strokeWeight: 1,
            rotation: 0,
            scale: 2,
            anchor: new google.maps.Point(0, 20),
          };
        }

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

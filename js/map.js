async function getPorches(){
	const res 	= fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?_embed&per_page=100`)
	const data	= (await res).json()
	const obj		= await data
	return obj
}

async function getPerformer(pfmr){
	const res 	= fetch(`${wpVars.homeURL}/wp-json/wp/v2/performers/${pfmr}`)
	const data 	= (await res).json()
	const obj		= await data
	return obj
}

async function buildPorches(){
	const porches = await getPorches()
	let data = []
	for(const porch of porches){
		let pfmrs = []
		for(let i = 1; i < 13; i++){
			// Technically a debug line:
			if(!porch.acf[`performer_${i}`])break
			if(!porch.acf[`performer_${i}`].performer)break
			await getPerformer(porch.acf[`performer_${i}`].performer)
			.then(pfmr=>pfmrs.push(pfmr))
		}
		porch.performers = pfmrs
		data.push(porch)
	}
	return data
}

function filterForm(params){
	const filterForm = document.createElement('form')
		filterForm.id = 'map-filter'
		const timeInput = document.createElement('input')
		timeInput.type = 'time'
		timeInput.id = 'time-input'
		timeInput.name = 'time-input'
		if (params.get('time-input')) timeInput.value = params.get('time-input')
		const timeInputLabel = document.createElement('label')
		timeInputLabel.htmlFor = 'time-input'
		timeInputLabel.appendChild(document.createTextNode('Starts After '))
		timeInputLabel.appendChild(timeInput)
		timeInput.addEventListener('change', (e) => {
			timeSelect = e.target.value
		})
		const hasFood = document.createElement('input')
		hasFood.type = 'checkbox'
		hasFood.name = 'Food'
		hasFood.value = 'Food'
		hasFood.id = 'has-food'
		if (params.has('Food')) hasFood.checked = true
		const hasFoodLabel = document.createElement('label')
		hasFoodLabel.htmlFor = 'has-food'
		hasFoodLabel.className = 'has-food-label'
		hasFoodLabel.appendChild(hasFood)
		hasFoodLabel.appendChild(document.createTextNode(' Food'))
		const hasPortaPotty = document.createElement('input')
		hasPortaPotty.type = 'checkbox'
		hasPortaPotty.name = 'Porta Potty'
		hasPortaPotty.value = 'Porta Potty'
		hasPortaPotty.id = 'has-porta-potty'
		if (params.has('Porta Potty')) hasPortaPotty.checked = true
		const hasPortaPottyLabel = document.createElement('label')
		hasPortaPottyLabel.htmlFor = 'has-porta-potty'
		hasPortaPottyLabel.appendChild(hasPortaPotty)
		hasPortaPottyLabel.appendChild(document.createTextNode(' Porta Potty'))
		const resetBtn = document.createElement('a')
		resetBtn.innerText = 'Reset'
		resetBtn.href = '/map'
		resetBtn.style =
			'margin-bottom:.5remcolor:#14A4ABtext-decoration:none'
		resetBtn.style.display = 'none'
		let filterShown = false
		const showToggle = document.createElement('a')
		showToggle.innerText = 'Show Filter'
		showToggle.href = '#'
		showToggle.style =
			'margin-bottom:.5remcolor:#14A4ABtext-decoration:none'
		showToggle.addEventListener('click', (e) => {
			e.preventDefault()
			if (!filterShown) {
				filterForm.style.height = 'initial'
				filterForm.style.width = 'initial'
				showToggle.innerText = 'Hide Filter'
				resetBtn.style.display = 'initial'
				filterShown = true
			} else {
				filterForm.style.height = '45px'
				filterForm.style.width = '103px'
				showToggle.innerText = 'Show Filter'
				resetBtn.style.display = 'none'
				filterShown = false
			}
		})
		const topDiv = document.createElement('div');
		topDiv.style = 'display: flex;justify-content:space-between;';
		topDiv.appendChild(resetBtn);
		topDiv.appendChild(showToggle);
		const submitBtn = document.createElement('button');
		submitBtn.type = 'submit';
		submitBtn.id = 'submit-btn';
		submitBtn.innerText = 'Submit';
		filterForm.appendChild(topDiv);
		filterForm.appendChild(timeInputLabel);
		const line2 = document.createElement('div');
		line2.appendChild(hasFoodLabel);
		line2.appendChild(hasPortaPottyLabel);
		filterForm.appendChild(line2);
		filterForm.appendChild(submitBtn);
		return filterForm
}

function directionsSubmit(){
	// Requests the users location
	if (window.navigator.geolocation) {
		window.navigator.geolocation.getCurrentPosition(
			locationSuccess,
			e=>console.log(`Location Rejected Error: ${e}`)
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
				console.log(`Directions Route Response: ${response}`);
				console.log(`Directions Route Status: ${status}`);
			}
		);
	}
	// Handles functionality if users location can not be requested
	function locationRejected(error) {
		console.log(`Location Rejected Error: ${error}`);
	}
}

// *************************************************
// Initialization of the map
function initMap(){
  const urlArgs = window.location.search;
  const params = new URLSearchParams(urlArgs);
  const timeArg = params.get('time-input')
    ? params.get('time-input').split(':')
    : [0, 0];

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

	buildPorches()
	.then(porches=>{
		const currentDate = new Date()
		// Will be users input
		let timeSelect = new Date(
			currentDate.getFullYear(),
			currentDate.getMonth(),
			currentDate.getDate(),
			...[...timeArg, 0]
		)
		document.getElementById('map').appendChild(filterForm(params))
		porches.map(porch=>{
			// Porch filtering:
			let showPorch = false
			if(params.get('time-input')){
				startTimes.forEach((time) => {
					if (showPorch) return
					time = time.split(':')
					if(time[0] < 10){
						time[0] = parseInt(time[0])
						time[0] += 12
					}
					let now = new Date()
					const porchTime = new Date(
						now.getFullYear(),
						now.getMonth(),
						now.getDate(),
						...time
					)
					if(porchTime >= timeSelect){
						showPorch = true
						return
					}
				})
			} else {
				showPorch = true
			}

			if(params.has('Food') && showPorch){
				if(
					porch.acf.tag_one.toLowerCase().includes('food') ||
					porch.acf.tag_two.toLowerCase().includes('food') ||
					porch.acf.tag_three.toLowerCase().includes('food')
				){
					showPorch = true
				}else{
					showPorch = false
				}
			}
			if(params.has('Porta Potty')){
				if(porch.acf.host_type === 'Porta Potty'){
					showPorch = true
				}
			}
			if(params.has('Porta Potty') && !params.has('Food')){
				if (porch.acf.host_type === 'Porta Potty') {
					showPorch = true
				}else{
					showPorch = false 
				}
			}
			if(!showPorch){
				return
			}
			// Initializing the variable that will contain the porch information

			// Marker color is set based upon porch information

			// If porch has a featured image it will use that, if not it will default to the porch fest logo
			let porchImage
			if (porch._embedded) {
				porchImage = porch._embedded['wp:featuredmedia'][0].source_url
			} else {
				porchImage = wpVars.defaultImageURL
			}

			// Construction of the CONTENT div responsible for populating info window
			const contentDiv = document.createElement('div')
			contentDiv.id = 'content'

			let contentString =
				`<img src=${porchImage} alt="porch" />` +
				'<div id="content-header">' +
				`<h3>${porch.title.rendered}</h3>` +
				`<p>${porch.acf.address}</p>` +
				'</div>' +
				'<div id="desc">' +
				`${porch.content.rendered}` +
				'</div>'
			let svgMarker
			let markerColor = '#462d62'
			if(porch.acf.info_booth){
				markerColor = '#F45050'
					svgMarker = {
						path: 'M 11.25 22.5 c -0.3538 0 -0.6813 -0.187 -0.861 -0.4915 l -1.0962 -1.8542 C 7.0418 16.349 4.916 12.755 4.1898 11.2973 c -0.528 -1.0828 -0.7937 -2.2388 -0.7937 -3.4432 C 3.396 3.5232 6.9192 0 11.25 0 c 4.3308 0 7.854 3.5232 7.854 7.854 c 0 1.2038 -0.2657 2.3595 -0.7893 3.4352 c -0.0063 0.013 -0.0132 0.026 -0.02 0.0387 c -0.7402 1.4772 -2.8525 5.0483 -5.0883 8.8272 l -1.0955 1.8533 C 11.9313 22.313 11.6037 22.5 11.25 22.5 z M 11.25 2.175 c -2.979 0 -5.4028 2.4235 -5.4028 5.4025 c 0 2.979 2.4238 5.4025 5.4028 5.4025 c 2.9787 0 5.4025 -2.4235 5.4025 -5.4025 C 16.6525 4.5985 14.2287 2.175 11.25 2.175 z M 11.25 11.717 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 V 7.3275 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 3.6395 C 12 11.3812 11.6642 11.717 11.25 11.717 z M 11.25 5.522 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 v -0.555 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 0.555 C 12 5.1863 11.6642 5.522 11.25 5.522 z',
						fillColor: markerColor,
						fillOpacity: 0.9,
						strokeWeight: 1,
						rotation: 0,
						scale: 2,
						anchor: new google.maps.Point(0, 20),
					}
			}else if(porch.acf.sponsored){
				markerColor = '#208f95'
			}else if(porch.acf.porta_potty){
				markerColor = '#ffff00'
				contentString =
					`<img src=${porchImage} alt="porch" />` +
					'<div id="content-header">' +
					`<h3>${porch.title.rendered}</h3>` +
					`<p>${porch.acf.address}</p>` +
					'</div>'
				// Creates custom marker
				svgMarker = {
					path: 'M 15.7302 0.3459 C 15.7302 0.3459 16.7835 0.3459 16.7835 0.3459 C 16.7835 0.3459 17.6964 0.447 17.6964 0.447 C 18.9563 0.6285 20.0546 1.0214 21.1725 1.626 C 21.9871 2.0667 22.7315 2.6474 23.3846 3.3005 C 24.4674 4.3834 25.2775 5.6172 25.8056 7.0575 C 26.103 7.8686 26.4028 9.1463 26.4042 10.0069 C 26.4042 10.0069 26.4042 11.271 26.4042 11.271 C 26.3993 11.693 26.2666 12.4634 26.1732 12.8861 C 25.8108 14.5265 24.9618 16.2031 23.8351 17.4507 C 22.3354 19.1111 20.2923 20.3021 18.0827 20.7013 C 17.1097 20.8769 16.4598 20.8678 15.4844 20.8565 C 14.7565 20.8477 13.6164 20.5967 12.9212 20.365 C 9.942 19.372 7.5803 17.1034 6.4975 14.1501 C 6.2001 13.339 5.9002 12.0613 5.8988 11.2007 C 5.8988 11.2007 5.8988 10.0069 5.8988 10.0069 C 5.8999 9.2295 6.1551 8.0796 6.402 7.3384 C 7.3128 4.6067 9.3889 2.3093 12.0434 1.1738 C 13.2499 0.6576 14.4233 0.4048 15.7302 0.3459 Z M 11.587 3.1352 C 11.2692 3.1885 11.0294 3.3128 10.8366 3.5814 C 10.3285 4.2907 10.6846 5.4634 11.6221 5.541 C 11.7566 5.5523 11.9188 5.5105 12.0434 5.4613 C 13.0167 5.0758 12.9655 3.5172 12.0083 3.1868 C 11.8542 3.1334 11.7481 3.1215 11.587 3.1352 Z M 16.2569 3.1355 C 15.707 3.2429 15.8355 3.5937 15.8355 4.0379 C 15.8355 4.0379 15.8355 6.6362 15.8355 6.6362 C 15.8355 6.6362 15.8355 15.3439 15.8355 15.3439 C 15.8355 15.3439 15.8355 17.4507 15.8355 17.4507 C 15.8376 17.8703 16.0831 17.9563 16.4675 17.9408 C 16.9454 17.9215 16.9587 17.7463 16.9591 17.3453 C 16.9591 17.3453 16.9591 6.3904 16.9591 6.3904 C 16.9591 6.3904 16.9591 3.6165 16.9591 3.6165 C 16.9591 3.5224 16.9668 3.3904 16.918 3.3079 C 16.805 3.1179 16.4496 3.1162 16.2569 3.1355 Z M 20.7512 3.1355 C 19.4032 3.3897 19.536 5.4346 20.8214 5.541 C 21.4443 5.5926 21.9313 4.9673 21.9446 4.389 C 21.9562 3.8778 21.7073 3.3802 21.2076 3.1934 C 21.056 3.1369 20.9113 3.1211 20.7512 3.1355 Z M 14.0448 12.9212 C 14.0448 12.9212 13.589 11.271 13.589 11.271 C 13.589 11.271 12.9212 8.9536 12.9212 8.9536 C 12.9212 8.9536 12.6607 8.0406 12.6607 8.0406 C 12.6336 7.9434 12.5687 7.7548 12.7151 7.7155 C 12.8201 7.6878 12.8689 7.7864 12.8991 7.8658 C 12.8991 7.8658 13.0919 8.4971 13.0919 8.4971 C 13.0919 8.4971 13.5982 10.1825 13.5982 10.1825 C 13.5982 10.1825 13.8489 10.982 13.8489 10.982 C 13.9904 11.219 14.5487 11.0522 14.6817 10.8461 C 14.7776 10.6979 14.7032 10.5424 14.6533 10.3932 C 14.6533 10.3932 14.3601 9.4802 14.3601 9.4802 C 14.3601 9.4802 13.6119 7.198 13.6119 7.198 C 13.3745 6.4859 13.213 5.9093 12.3243 5.8988 C 12.3243 5.8988 11.3412 5.8988 11.3412 5.8988 C 11.0448 5.8988 10.7689 5.8869 10.4985 6.0358 C 10.1249 6.2419 9.982 6.6404 9.8549 7.0224 C 9.8549 7.0224 9.1986 9.0589 9.1986 9.0589 C 9.1986 9.0589 8.7773 10.358 8.7773 10.358 C 8.7095 10.5754 8.5986 10.7576 8.817 10.9325 C 8.9634 11.0501 9.3696 11.1793 9.5259 11.0343 C 9.6084 10.9578 9.7362 10.4588 9.7759 10.3229 C 9.7759 10.3229 10.3222 8.4971 10.3222 8.4971 C 10.3222 8.4971 10.4999 7.9002 10.4999 7.9002 C 10.5238 7.83 10.5624 7.7088 10.6502 7.6997 C 10.8113 7.6832 10.7976 7.8711 10.7738 7.9704 C 10.7738 7.9704 10.4992 8.9184 10.4992 8.9184 C 10.4992 8.9184 9.7713 11.4465 9.7713 11.4465 C 9.7713 11.4465 9.3749 12.9212 9.3749 12.9212 C 9.3749 12.9212 10.5687 12.9212 10.5687 12.9212 C 10.5687 12.9212 10.5687 17.4858 10.5687 17.4858 C 10.5691 17.5855 10.5571 17.7017 10.6249 17.7832 C 10.7875 17.9784 11.3219 18.0079 11.4957 17.7628 C 11.5613 17.6705 11.5515 17.5581 11.5518 17.4507 C 11.5518 17.4507 11.5518 13.483 11.5518 13.483 C 11.5518 13.3784 11.5462 13.055 11.7253 13.0834 C 11.878 13.1077 11.8675 13.3717 11.8679 13.483 C 11.8679 13.483 11.8679 17.4858 11.8679 17.4858 C 11.8682 17.5855 11.8563 17.7017 11.924 17.7853 C 12.0933 17.9865 12.6284 17.9854 12.7948 17.7853 C 12.8619 17.7073 12.8506 17.5841 12.851 17.4858 C 12.851 17.4858 12.851 12.9212 12.851 12.9212 C 12.851 12.9212 14.0448 12.9212 14.0448 12.9212 Z M 19.4169 5.9041 C 18.575 6.0487 18.3028 6.6137 18.2934 7.4086 C 18.2934 7.4086 18.2934 10.9901 18.2934 10.9901 C 18.2937 11.1003 18.2821 11.2362 18.3544 11.3306 C 18.5191 11.536 19.0219 11.5518 19.1852 11.3306 C 19.2516 11.2453 19.241 11.1295 19.2414 11.0252 C 19.2414 11.0252 19.2414 8.1109 19.2414 8.1109 C 19.2414 8.0178 19.234 7.8812 19.2825 7.8001 C 19.353 7.6815 19.4865 7.6822 19.5567 7.8001 C 19.5992 7.8718 19.5925 7.9936 19.5925 8.0758 C 19.5925 8.0758 19.5925 17.4858 19.5925 17.4858 C 19.5928 17.5837 19.5809 17.7031 19.6487 17.787 C 19.8176 17.9795 20.4594 18.0002 20.6248 17.787 C 20.6912 17.7084 20.6806 17.5869 20.681 17.4858 C 20.681 17.4858 20.681 14.6066 20.681 14.6066 C 20.681 14.6066 20.681 12.1839 20.681 12.1839 C 20.6824 12.068 20.6915 11.8629 20.7716 11.7738 C 20.8123 11.7285 20.8643 11.7042 20.9253 11.7183 C 21.11 11.7615 21.102 12.0041 21.1023 12.1488 C 21.1023 12.1488 21.1023 17.4858 21.1023 17.4858 C 21.1027 17.5858 21.0914 17.7059 21.1585 17.7877 C 21.3358 18.0047 21.9777 17.9738 22.1346 17.7877 C 22.2017 17.7073 22.1904 17.5841 22.1908 17.4858 C 22.1908 17.4858 22.1908 8.1109 22.1908 8.1109 C 22.1908 8.0178 22.1834 7.8812 22.2319 7.8001 C 22.3024 7.6815 22.4359 7.6822 22.5061 7.8001 C 22.5486 7.8718 22.5419 7.9936 22.5419 8.0758 C 22.5419 8.0758 22.5419 11.0252 22.5419 11.0252 C 22.5419 11.095 22.538 11.2056 22.563 11.2695 C 22.6328 11.4479 22.8972 11.503 23.0686 11.4893 C 23.4264 11.4599 23.4896 11.3092 23.4899 10.9901 C 23.4899 10.9901 23.4899 7.3384 23.4899 7.3384 C 23.4892 6.9437 23.3934 6.508 23.0977 6.2254 C 22.8186 5.9585 22.4552 5.9016 22.0854 5.9041 C 22.0854 5.9041 19.4169 5.9041 19.4169 5.9041 Z',
					fillColor: markerColor,
					fillOpacity: 0.9,
					strokeWeight: 1,
					rotation: 0,
					scale: 2,
					anchor: new google.maps.Point(0, 20),
				}
			}
			else{
				svgMarker = {
					path: 'M 0 0 q 2.906 0 4.945 2.039 t 2.039 4.945 q 0 1.453 -0.727 3.328 t -1.758 3.516 t -2.039 3.07 t -1.711 2.273 l -0.75 0.797 q -0.281 -0.328 -0.75 -0.867 t -1.688 -2.156 t -2.133 -3.141 t -1.664 -3.445 t -0.75 -3.375 q 0 -2.906 2.039 -4.945 t 4.945 -2.039 z',
					fillColor: markerColor,
					fillOpacity: 0.9,
					strokeWeight: 1,
					rotation: 0,
					scale: 2,
					anchor: new google.maps.Point(0, 20),
				}
			}

			contentDiv.innerHTML = contentString

			let tdString = ``
			if(porch.performers){
				for(let i = 0; i < porch.performers.length; i++){
				  tdString +=
				    `<tr>` +
						`<td>${porch.acf[`performer_${i+1}`].start_time}</td>` +
				    `<td>${porch.performers[i].title.rendered}</td>` +
				    `</tr>`;
				}
			}
			const contentContainer = document.createElement('div')
			contentContainer.className = 'content-container'
			if(tdString){
				contentContainer.innerHTML =
					`<div class="lineup">` +
					`<table class="lineup-table">` +
					`<tbody>` +
					`<tr>` +
					`<th>START TIME</th>` +
					`<th>PERFORMER</th>` +
					`</tr>` +
					tdString +
					`</tbody>` +
					`</table>` +
					`</div>`
			}
			const directionsBtn = document.createElement('button');
			directionsBtn.type = 'button';
			directionsBtn.className = 'directions-btn';
			directionsBtn.textContent = 'Get Directions';
			contentContainer.appendChild(directionsBtn);
			const seeLineupBtn = document.createElement('a');
			seeLineupBtn.className = 'lineup-btn';
			seeLineupBtn.href = porch.link;
			seeLineupBtn.textContent = 'See Porch';
			seeLineupBtn.addEventListener('click', () => {
				sessionStorage.setItem('currentOpenPorch', JSON.stringify(porch));
			});
			contentContainer.appendChild(seeLineupBtn);
			contentDiv.appendChild(contentContainer);

			// Event listener to trigger the directions/routing
			directionsBtn.addEventListener('click', directionsSubmit)

			const lat = Number(porch.acf.latitude)
			const lng = Number(porch.acf.longitude)

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

			if (window.location.hash) {
				const openCardParams = window.location.hash
					.split('#')[1]
					.split('%20')
					.join(' ');

				const textArea = document.createElement('textarea');
				textArea.innerHTML = porch.title.rendered;

				if (openCardParams) {
					if (textArea.value === decodeURI(openCardParams)) {
						const openedMarker = new google.maps.Marker({
							position: { lat, lng },
							map,
							icon: svgMarker,
						});
						openInfoWindow = infoWindow;
						openInfoWindow.open({
							anchor: openedMarker,
							map,
						});
						map.addListener('click', () => {
							openInfoWindow.close();
						});
						markers.forEach((marker) => {
							marker.addListener('click', () => {
								openInfoWindow.close();
							});
						});
					}
				}
			}

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
	})

  // Fetch for all of the porches
}
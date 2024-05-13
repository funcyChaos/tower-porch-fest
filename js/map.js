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
	const genreSelect = document.createElement('select')
	genreSelect.name = 'genre'
	genreSelect.id = 'genre'
	const defOpt = document.createElement('option')
	if(params.get('genre') == 'all')defOpt.selected = true
	defOpt.value = 'All'
	defOpt.innerText = 'All Genres'
	genreSelect.appendChild(defOpt)
	for(const genre of Object.keys(wpVars.genres)){
		const option = document.createElement('option')
		if(params.get('genre') == genre)option.selected = true
		option.value = genre
		option.innerText = genre
		genreSelect.appendChild(option)
	}
	const searchLabel = document.createElement('label')
	searchLabel.htmlFor = 'srch'
	searchLabel.innerText = 'Search: '
	const searchBox = document.createElement('input')
	searchBox.type = 'text'
	searchBox.name = 'srch'
	const topDiv = document.createElement('div')
	topDiv.style = 'display: flex;justify-content:space-between;'
	topDiv.appendChild(resetBtn)
	topDiv.appendChild(showToggle)
	const submitBtn = document.createElement('button')
	submitBtn.type = 'submit'
	submitBtn.id = 'submit-btn'
	submitBtn.innerText = 'Submit'
	filterForm.appendChild(topDiv)
	filterForm.appendChild(timeInputLabel)
	filterForm.appendChild(genreSelect)
	const line2 = document.createElement('div')
	line2.appendChild(hasFoodLabel)
	line2.appendChild(hasPortaPottyLabel)
	const line3 = document.createElement('div')
	line3.appendChild(searchLabel)
	line3.appendChild(searchBox)
	filterForm.appendChild(line2)
	filterForm.appendChild(line3)
	filterForm.appendChild(submitBtn)
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

	fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?_embed&per_page=100`)
	.then(res=>res.json())
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
		let porchNumber = 0
		porches.map(porch=>{
			// ********************************************
			// Filters Begin
			porchNumber++
			let showPorch = false
			const testTime = ()=>{
				let bool = false
				if(porch.performers){
					let now = new Date()
					for(let i = 1; i < porch.performers.length + 1; i++){
						let time = porch.acff[`performer_${i}`].start_time
						if(time){
							time = time.split(':')
							const porchTime = new Date(
								now.getFullYear(),
								now.getMonth(),
								now.getDate(),
								...time
							)
							if(porchTime >= timeSelect){
								bool = true
								break
							}
						}
					}
				}
				return bool
			}
			const testGenre	= ()=>{
				if(porch.performers.length != 0){
					let bool = false
					for(let i = 0; i < porch.performers.length; i++){
						if(bool)break
						if(porch.performers[i][1] != null && porch.performers[i][1].length != 0){
							genreloop:
								for(let index = 0; index < porch.performers[i][1].length; index++){
									if(porch.performers[i][1][index] == params.get('genre')){
										bool = true
										break genreloop
									}else bool = false
								}
						}
					}
					return bool
				}else return false
			}
			const testSearch			= ()=>{
				let bool = false
				for (let i = 0; i < searchResults.length; i++){
					if(searchResults[i] == porch.id){
						bool = true
						break
					}
					for(let p = 0; p < porch.performers.length; p++){
						if(searchResults[i] == porch.performers[p][0].ID){
							bool = true
							break
						}
					}
				}
				return bool
			}
			const testFood				= ()=>porch.acff.has_food
			const testPortaPotty	= ()=>porch.acff.porta_potty
			let tests = []
			if(params.has('Porta Potty'))tests.push(testPortaPotty)
			if(params.has('Food'))tests.push(testFood)
			if(params.has('genre') && params.get('genre') != 'All')tests.push(testGenre)
			if(params.get('time-input'))tests.push(testTime)
			if(params.get('srch'))tests.push(testSearch)
			if(tests.length){
				for(const test of tests){
					if(test()){
						showPorch = true
						break
					}else {
						showPorch = false
					}
					// if('testPortaPotty' == test.name){
					// 	if(test()){
					// 		showPorch = true
					// 		break
					// 	}
					// }else{
					// 	if(!test()){
					// 		showPorch = false
					// 		break
					// 	}else showPorch = true
					// }
				}
			}else showPorch = true
			if(!showPorch)return
			// Filters End
			// ********************************************

			// If porch has a featured image it will use that, if not it will default to the porch fest logo
			let porchImage
			if(porch._embedded['wp:featuredmedia']){
				porchImage = porch._embedded['wp:featuredmedia'][0].source_url
			}else{
				porchImage = wpVars.defaultImageURL
			}

			// Construction of the CONTENT div responsible for populating info window
			const contentDiv = document.createElement('div')
			contentDiv.id = 'content'

			let contentString =
				`<img src=${porchImage} alt="porch" />` +
				'<div id="content-header">' +
				`<h3>${porch.title.rendered}</h3>` +
				`<p>${porch.acff.porch_address}</p>` +
				'</div>' +
				'<div id="desc">' +
				`${porch.content.rendered}` +
				'</div>'
			let markerPath = 'M 0 0 q 2.906 0 4.945 2.039 t 2.039 4.945 q 0 1.453 -0.727 3.328 t -1.758 3.516 t -2.039 3.07 t -1.711 2.273 l -0.75 0.797 q -0.281 -0.328 -0.75 -0.867 t -1.688 -2.156 t -2.133 -3.141 t -1.664 -3.445 t -0.75 -3.375 q 0 -2.906 2.039 -4.945 t 4.945 -2.039 z'
			
			let markerColor = '#462d62'

			if(porch.acff.info_booth){
				markerColor = '#F45050'
				markerPath = 'M 11.25 22.5 c -0.3538 0 -0.6813 -0.187 -0.861 -0.4915 l -1.0962 -1.8542 C 7.0418 16.349 4.916 12.755 4.1898 11.2973 c -0.528 -1.0828 -0.7937 -2.2388 -0.7937 -3.4432 C 3.396 3.5232 6.9192 0 11.25 0 c 4.3308 0 7.854 3.5232 7.854 7.854 c 0 1.2038 -0.2657 2.3595 -0.7893 3.4352 c -0.0063 0.013 -0.0132 0.026 -0.02 0.0387 c -0.7402 1.4772 -2.8525 5.0483 -5.0883 8.8272 l -1.0955 1.8533 C 11.9313 22.313 11.6037 22.5 11.25 22.5 z M 11.25 2.175 c -2.979 0 -5.4028 2.4235 -5.4028 5.4025 c 0 2.979 2.4238 5.4025 5.4028 5.4025 c 2.9787 0 5.4025 -2.4235 5.4025 -5.4025 C 16.6525 4.5985 14.2287 2.175 11.25 2.175 z M 11.25 11.717 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 V 7.3275 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 3.6395 C 12 11.3812 11.6642 11.717 11.25 11.717 z M 11.25 5.522 c -0.4143 0 -0.75 -0.3357 -0.75 -0.75 v -0.555 c 0 -0.4143 0.3357 -0.75 0.75 -0.75 c 0.4143 0 0.75 0.3357 0.75 0.75 v 0.555 C 12 5.1863 11.6642 5.522 11.25 5.522 z'
			}else if(porch.acff.party){
				markerColor = '#208f95'
				markerPath = 'M1.36 7.92C2.7646 7.92 3.703 7.4359 4.3154 6.6195 4.8593 5.8943 5.092 4.9621 5.2823 4.1997L5.2985 4.1346C5.51 3.2885 5.6848 2.6335 6.0366 2.1645 6.3356 1.766 6.8146 1.44 7.84 1.44 8.2377 1.44 8.56 1.1176 8.56.72 8.56.3224 8.2377 0 7.84 0 6.4354 0 5.497.484 4.8846 1.3005 4.3407 2.0257 4.108 2.9579 3.9177 3.7203L3.9015 3.7854C3.69 4.6315 3.5152 5.2865 3.1634 5.7555 2.8644 6.154 2.3854 6.48 1.36 6.48.9623 6.48.64 6.8024.64 7.2.64 7.5977.9623 7.92 1.36 7.92ZM-1.0868.1449C-1.4048-.0938-1.8561-.0295-2.0948.2885-2.3335.6066-2.2692 1.0579-1.9512 1.2966-1.6487 1.5236-1.3939 1.808-1.2013 2.1335-1.0087 2.459-.8822 2.8193-.8288 3.1937-.7754 3.5681-.7963 3.9494-.8903 4.3157-.9843 4.6821-1.1496 5.0263-1.3766 5.3288-1.6153 5.6468-1.551 6.0981-1.233 6.3368-.9149 6.5755-.4636 6.5112-.2249 6.1932.1156 5.7395.3635 5.2231.5045 4.6736.6455 4.1241.6769 3.5522.5968 2.9905.5167 2.4289.3269 1.8885.038 1.4003-.2509.912-.633.4854-1.0868.1449ZM-2.96 3.6C-2.96 3.9976-3.2824 4.32-3.68 4.32-4.0776 4.32-4.4 3.9976-4.4 3.6-4.4 3.2024-4.0776 2.88-3.68 2.88-3.2824 2.88-2.96 3.2024-2.96 3.6ZM7.84 6.48C8.2377 6.48 8.56 6.1576 8.56 5.76 8.56 5.3624 8.2377 5.04 7.84 5.04 7.4423 5.04 7.12 5.3624 7.12 5.76 7.12 6.1576 7.4423 6.48 7.84 6.48ZM6.4 12.96C6.4 13.3577 6.0777 13.68 5.68 13.68 5.2823 13.68 4.96 13.3577 4.96 12.96 4.96 12.5623 5.2823 12.24 5.68 12.24 6.0777 12.24 6.4 12.5623 6.4 12.96ZM6.0877 10.109C5.7134 10.0554 5.3321 10.0762 4.9657 10.17 4.5994 10.2638 4.255 10.4288 3.9524 10.6557 3.6343 10.8942 3.183 10.8297 2.9444 10.5116 2.7059 10.1934 2.7704 9.7421 3.0886 9.5036 3.5425 9.1633 4.059 8.9157 4.6086 8.775 5.1581 8.6342 5.73 8.6032 6.2916 8.6835 6.8532 8.7638 7.3935 8.954 7.8816 9.2431 8.3698 9.5322 8.7961 9.9147 9.1364 10.3686 9.375 10.6867 9.3105 11.138 8.9923 11.3766 8.6741 11.6151 8.2228 11.5505 7.9843 11.2324 7.7574 10.9297 7.4732 10.6748 7.1478 10.482 6.8224 10.2893 6.4621 10.1626 6.0877 10.109ZM-3.1709 6.6909C-3.3432 6.5186-3.591 6.4451-3.8294 6.4957-4.0679 6.5463-4.2645 6.7141-4.352 6.9415L-5.3543 9.5476-.2675 14.6344 2.3385 13.632C2.566 13.5445 2.7338 13.3479 2.7843 13.1095 2.8349 12.8711 2.7615 12.6233 2.5891 12.4509L-3.1709 6.6909ZM-1.7383 15.2-5.92 11.0183-7.952 16.3015C-8.0541 16.5671-7.9903 16.8679-7.7891 17.0691-7.5879 17.2704-7.2871 17.3341-7.0215 17.232L-1.7383 15.2Z'
			}else if(porch.acff.parking){
				markerColor = '#00F'
				markerPath = 'M-6.24.08C-7.652.08-8.8 1.228-8.8 2.64V15.44c0 1.412 1.148 2.56 2.56 2.56H6.56c1.412 0 2.56-1.148 2.56-2.56V2.64c0-1.412-1.148-2.56-2.56-2.56H-6.24zM-1.12 9.04h1.92c.708 0 1.28-.572 1.28-1.28s-.572-1.28-1.28-1.28H-1.12v2.56zm1.92 2.56H-1.12v1.28c0 .708-.572 1.28-1.28 1.28s-1.28-.572-1.28-1.28V10.32 5.52c0-.884.716-1.6 1.6-1.6h2.88c2.12 0 3.84 1.72 3.84 3.84s-1.72 3.84-3.84 3.84z'
			}else if(porch.acff.sponsored){
				markerColor = '#208f95'
			}else if(porch.acff.porta_potty){
				markerColor = '#ffff00'
				contentString =
					`<img src=${porchImage} alt="porch" />` +
					'<div id="content-header">' +
					`<h3>${porch.title.rendered}</h3>` +
					`<p>${porch.acff.porch_address}</p>` +
					'</div>'
				markerPath = 'M 15.7302 0.3459 C 15.7302 0.3459 16.7835 0.3459 16.7835 0.3459 C 16.7835 0.3459 17.6964 0.447 17.6964 0.447 C 18.9563 0.6285 20.0546 1.0214 21.1725 1.626 C 21.9871 2.0667 22.7315 2.6474 23.3846 3.3005 C 24.4674 4.3834 25.2775 5.6172 25.8056 7.0575 C 26.103 7.8686 26.4028 9.1463 26.4042 10.0069 C 26.4042 10.0069 26.4042 11.271 26.4042 11.271 C 26.3993 11.693 26.2666 12.4634 26.1732 12.8861 C 25.8108 14.5265 24.9618 16.2031 23.8351 17.4507 C 22.3354 19.1111 20.2923 20.3021 18.0827 20.7013 C 17.1097 20.8769 16.4598 20.8678 15.4844 20.8565 C 14.7565 20.8477 13.6164 20.5967 12.9212 20.365 C 9.942 19.372 7.5803 17.1034 6.4975 14.1501 C 6.2001 13.339 5.9002 12.0613 5.8988 11.2007 C 5.8988 11.2007 5.8988 10.0069 5.8988 10.0069 C 5.8999 9.2295 6.1551 8.0796 6.402 7.3384 C 7.3128 4.6067 9.3889 2.3093 12.0434 1.1738 C 13.2499 0.6576 14.4233 0.4048 15.7302 0.3459 Z M 11.587 3.1352 C 11.2692 3.1885 11.0294 3.3128 10.8366 3.5814 C 10.3285 4.2907 10.6846 5.4634 11.6221 5.541 C 11.7566 5.5523 11.9188 5.5105 12.0434 5.4613 C 13.0167 5.0758 12.9655 3.5172 12.0083 3.1868 C 11.8542 3.1334 11.7481 3.1215 11.587 3.1352 Z M 16.2569 3.1355 C 15.707 3.2429 15.8355 3.5937 15.8355 4.0379 C 15.8355 4.0379 15.8355 6.6362 15.8355 6.6362 C 15.8355 6.6362 15.8355 15.3439 15.8355 15.3439 C 15.8355 15.3439 15.8355 17.4507 15.8355 17.4507 C 15.8376 17.8703 16.0831 17.9563 16.4675 17.9408 C 16.9454 17.9215 16.9587 17.7463 16.9591 17.3453 C 16.9591 17.3453 16.9591 6.3904 16.9591 6.3904 C 16.9591 6.3904 16.9591 3.6165 16.9591 3.6165 C 16.9591 3.5224 16.9668 3.3904 16.918 3.3079 C 16.805 3.1179 16.4496 3.1162 16.2569 3.1355 Z M 20.7512 3.1355 C 19.4032 3.3897 19.536 5.4346 20.8214 5.541 C 21.4443 5.5926 21.9313 4.9673 21.9446 4.389 C 21.9562 3.8778 21.7073 3.3802 21.2076 3.1934 C 21.056 3.1369 20.9113 3.1211 20.7512 3.1355 Z M 14.0448 12.9212 C 14.0448 12.9212 13.589 11.271 13.589 11.271 C 13.589 11.271 12.9212 8.9536 12.9212 8.9536 C 12.9212 8.9536 12.6607 8.0406 12.6607 8.0406 C 12.6336 7.9434 12.5687 7.7548 12.7151 7.7155 C 12.8201 7.6878 12.8689 7.7864 12.8991 7.8658 C 12.8991 7.8658 13.0919 8.4971 13.0919 8.4971 C 13.0919 8.4971 13.5982 10.1825 13.5982 10.1825 C 13.5982 10.1825 13.8489 10.982 13.8489 10.982 C 13.9904 11.219 14.5487 11.0522 14.6817 10.8461 C 14.7776 10.6979 14.7032 10.5424 14.6533 10.3932 C 14.6533 10.3932 14.3601 9.4802 14.3601 9.4802 C 14.3601 9.4802 13.6119 7.198 13.6119 7.198 C 13.3745 6.4859 13.213 5.9093 12.3243 5.8988 C 12.3243 5.8988 11.3412 5.8988 11.3412 5.8988 C 11.0448 5.8988 10.7689 5.8869 10.4985 6.0358 C 10.1249 6.2419 9.982 6.6404 9.8549 7.0224 C 9.8549 7.0224 9.1986 9.0589 9.1986 9.0589 C 9.1986 9.0589 8.7773 10.358 8.7773 10.358 C 8.7095 10.5754 8.5986 10.7576 8.817 10.9325 C 8.9634 11.0501 9.3696 11.1793 9.5259 11.0343 C 9.6084 10.9578 9.7362 10.4588 9.7759 10.3229 C 9.7759 10.3229 10.3222 8.4971 10.3222 8.4971 C 10.3222 8.4971 10.4999 7.9002 10.4999 7.9002 C 10.5238 7.83 10.5624 7.7088 10.6502 7.6997 C 10.8113 7.6832 10.7976 7.8711 10.7738 7.9704 C 10.7738 7.9704 10.4992 8.9184 10.4992 8.9184 C 10.4992 8.9184 9.7713 11.4465 9.7713 11.4465 C 9.7713 11.4465 9.3749 12.9212 9.3749 12.9212 C 9.3749 12.9212 10.5687 12.9212 10.5687 12.9212 C 10.5687 12.9212 10.5687 17.4858 10.5687 17.4858 C 10.5691 17.5855 10.5571 17.7017 10.6249 17.7832 C 10.7875 17.9784 11.3219 18.0079 11.4957 17.7628 C 11.5613 17.6705 11.5515 17.5581 11.5518 17.4507 C 11.5518 17.4507 11.5518 13.483 11.5518 13.483 C 11.5518 13.3784 11.5462 13.055 11.7253 13.0834 C 11.878 13.1077 11.8675 13.3717 11.8679 13.483 C 11.8679 13.483 11.8679 17.4858 11.8679 17.4858 C 11.8682 17.5855 11.8563 17.7017 11.924 17.7853 C 12.0933 17.9865 12.6284 17.9854 12.7948 17.7853 C 12.8619 17.7073 12.8506 17.5841 12.851 17.4858 C 12.851 17.4858 12.851 12.9212 12.851 12.9212 C 12.851 12.9212 14.0448 12.9212 14.0448 12.9212 Z M 19.4169 5.9041 C 18.575 6.0487 18.3028 6.6137 18.2934 7.4086 C 18.2934 7.4086 18.2934 10.9901 18.2934 10.9901 C 18.2937 11.1003 18.2821 11.2362 18.3544 11.3306 C 18.5191 11.536 19.0219 11.5518 19.1852 11.3306 C 19.2516 11.2453 19.241 11.1295 19.2414 11.0252 C 19.2414 11.0252 19.2414 8.1109 19.2414 8.1109 C 19.2414 8.0178 19.234 7.8812 19.2825 7.8001 C 19.353 7.6815 19.4865 7.6822 19.5567 7.8001 C 19.5992 7.8718 19.5925 7.9936 19.5925 8.0758 C 19.5925 8.0758 19.5925 17.4858 19.5925 17.4858 C 19.5928 17.5837 19.5809 17.7031 19.6487 17.787 C 19.8176 17.9795 20.4594 18.0002 20.6248 17.787 C 20.6912 17.7084 20.6806 17.5869 20.681 17.4858 C 20.681 17.4858 20.681 14.6066 20.681 14.6066 C 20.681 14.6066 20.681 12.1839 20.681 12.1839 C 20.6824 12.068 20.6915 11.8629 20.7716 11.7738 C 20.8123 11.7285 20.8643 11.7042 20.9253 11.7183 C 21.11 11.7615 21.102 12.0041 21.1023 12.1488 C 21.1023 12.1488 21.1023 17.4858 21.1023 17.4858 C 21.1027 17.5858 21.0914 17.7059 21.1585 17.7877 C 21.3358 18.0047 21.9777 17.9738 22.1346 17.7877 C 22.2017 17.7073 22.1904 17.5841 22.1908 17.4858 C 22.1908 17.4858 22.1908 8.1109 22.1908 8.1109 C 22.1908 8.0178 22.1834 7.8812 22.2319 7.8001 C 22.3024 7.6815 22.4359 7.6822 22.5061 7.8001 C 22.5486 7.8718 22.5419 7.9936 22.5419 8.0758 C 22.5419 8.0758 22.5419 11.0252 22.5419 11.0252 C 22.5419 11.095 22.538 11.2056 22.563 11.2695 C 22.6328 11.4479 22.8972 11.503 23.0686 11.4893 C 23.4264 11.4599 23.4896 11.3092 23.4899 10.9901 C 23.4899 10.9901 23.4899 7.3384 23.4899 7.3384 C 23.4892 6.9437 23.3934 6.508 23.0977 6.2254 C 22.8186 5.9585 22.4552 5.9016 22.0854 5.9041 C 22.0854 5.9041 19.4169 5.9041 19.4169 5.9041 Z'
			}
			if(porch.acff.has_food){
				markerPath = 'M 0 0, q 2.906 0 4.945 2.039, t 2.039 4.945, q 0 1.453 -0.727 3.328, t -1.758 3.516, t -2.039 3.07, t -1.711 2.273, l -0.75 0.797, q -0.281 -0.328 -0.75 -0.867, t -1.688 -2.156, t -2.133 -3.141, t -1.664 -3.445, t -0.75 -3.375, q 0 -2.906 2.039 -4.945, t 4.945 -2.039, z, M 2.5816 3.9656, C 2.3328 3.9656 .591 4.4633 .591 6.7028, V 8.4446, c 0 .549 .4463 .9953 .9953 .9953, h .4977, V 11.4306, c 0 .2753 .2224 .4977 .4977 .4977, s .4977 -.2224 .4977 -.4977, V 9.4399, 7.6981, 4.4633, c 0 -.2753 -.2224 -.4977 -.4977 -.4977, Z, M -2.8927 4.2144, C -2.8927 4.0869 -2.9875 3.9812 -3.1151 3.9672, S -3.3561 4.0371 -3.3841 4.16, L -3.8553 6.2797, C -3.8771 6.3777 -3.888 6.4772 -3.888 6.5768, c 0 .7138 .5459 1.3001 1.2442 1.3639, V 11.4306, c 0 .2753 .2224 .4977 .4977 .4977, s .4977 -.2224 .4977 -.4977, V 7.9407, c .6983 -.0638 1.2442 -.6501 1.2442 -1.3639, c 0 -.0995 -.0109 -.1991 -.0327 -.297, L -.9082 4.16, c -.028 -.1244 -.1446 -.2068 -.2706 -.1928, S -1.3997 4.0869 -1.3997 4.2144, V 6.3015, c 0 .084 -.0684 .1524 -.1524 .1524, c -.0793 0 -.1446 -.0607 -.1524 -.14, L -1.8989 4.1927, C -1.9098 4.0636 -2.0171 3.9656 -2.1462 3.9656, s -.2364 .098 -.2473 .2271, L -2.5863 6.314, c -.0078 .0793 -.0731 .14 -.1524 .14, c -.084 0 -.1524 -.0684 -.1524 -.1524, V 4.2144, z, m .7512 2.3639, -.0047 0, -.0047 0, .0047 -.0109, .0047 .0109, z'
			}
			let svgMarker = {
				path: markerPath,
				fillColor: markerColor,
				fillOpacity: 0.9,
				strokeWeight: 1,
				rotation: 0,
				scale: 2,
				anchor: new google.maps.Point(0, 20),
			}
			contentDiv.innerHTML = contentString
			let tdString = ``
			if(porch.performers.length != 0){
				// Bug: This should only be running twice...
				for(let i = 0; i < porch.performers.length; i++){
					let start_time
					if(porch.acff[`performer_${i+1}`].start_time){
						const milTime = porch.acff[`performer_${i+1}`].start_time.split(':')
						const hours = Number(milTime[0])
						const minutes = Number(milTime[1])
						if(hours > 0 && hours <= 12){
							start_time = "" + hours
						}else if(hours > 12){
							start_time = "" + (hours - 12)
						}else if(hours == 0){
							start_time = "12"
						}
						start_time += minutes < 10 ? ":0" + minutes : ":" + minutes
						start_time += hours >= 12 ? "PM" : "AM"
					}
				  tdString +=
				    `<tr>` +
						`<td>${start_time}</td>` +
				    `<td>${porch.performers[i][0].post_title}</td>` +
				    `</tr>`
				}
				if(porch.acff.has_food){
					tdString +=
					`<tr>` +
					`<td>Food!</td>` +
					`<td>${porch.acff.food_vendor.food_name}</td>` +
					`</tr>`
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
			const directionsBtn = document.createElement('button')
			directionsBtn.type = 'button'
			directionsBtn.className = 'directions-btn'
			directionsBtn.textContent = 'Get Directions'
			contentContainer.appendChild(directionsBtn)
			const seeLineupBtn = document.createElement('a')
			seeLineupBtn.className = 'lineup-btn'
			seeLineupBtn.href = porch.link
			seeLineupBtn.textContent = 'See Porch'
			seeLineupBtn.addEventListener('click', () => {
				sessionStorage.setItem('currentOpenPorch', JSON.stringify(porch))
			})
			contentContainer.appendChild(seeLineupBtn)
			contentDiv.appendChild(contentContainer)

			// Event listener to trigger the directions/routing
			directionsBtn.addEventListener('click', directionsSubmit)

			const lat = Number(porch.acff.latitude)
			const lng = Number(porch.acff.longitude)

			// Places marker on the map for each porch
			
			// says deprecated lmao use google.maps.marker.AdvancedMarkerElement (but it doesn't work x.x)
			const marker = new google.maps.Marker({
				position: {lat, lng},
				map,
				icon: svgMarker,
				// label: {className: 'marker-label', text: `${porchNumber}`}
			})

			// Creates a info window for each marker
			const infoWindow = new google.maps.InfoWindow({
				content: contentDiv,
			})

			infoWindows.push(infoWindow)
			markers.push(marker)

			if (window.location.hash) {
				const openCardParams = window.location.hash
					.split('#')[1]
					.split('%20')
					.join(' ')

				const textArea = document.createElement('textarea')
				textArea.innerHTML = porch.title.rendered

				if (openCardParams) {
					if (textArea.value === decodeURI(openCardParams)) {
						const openedMarker = new google.maps.Marker({
							position: { lat, lng },
							map,
							icon: svgMarker,
						})
						openInfoWindow = infoWindow;
						openInfoWindow.open({
							anchor: openedMarker,
							map,
						})
						map.addListener('click', () => {
							openInfoWindow.close();
						})
						markers.forEach((marker) => {
							marker.addListener('click', () => {
								openInfoWindow.close();
							})
						})
					}
				}
			}

			//end of loop
		});

		// Handles the open/close functionality of info window when clicking on other markers
		for(let i = 0; i < markers.length; i++){
			markers[i].addListener('click', ()=>{
				if (openInfoWindow && openInfoWindow != infoWindows[i]) {
					openInfoWindow.close();
				}
				openInfoWindow = infoWindows[i];

				infoWindows[i].open({
					anchor: markers[i],
					map,
				})
			})
		}

		// Closes info window when map is clicked
		map.addListener('click', function(){
			if(openInfoWindow)openInfoWindow.close()
		})
	})

  // Fetch for all of the porches
}
let matches = []

async function initMap() {
	const { ColorScheme } = await google.maps.importLibrary("core")
	// --- START: Constants ---
	const INITIAL_CENTER = { lat: 36.7650533, lng: -119.7995578 }; // Seems to be info booth
	const INITIAL_ZOOM = 14.2;
	const MIN_ZOOM_OFFSET = 2;
	const MAX_ZOOM_OFFSET = 3;
	const MARKER_OFFSET_FACTOR = 0.0002;
	const PANEL_PAN_OFFSET_Y = 0.005; // Adjust this value experimentally for bottom panel panning
	const DEFAULT_FALLBACK_IMAGE_URL = "https://towerporchfest.org/wp-content/uploads/2025/01/Untitled-1803-x-670-px1.png";
	// --- END: Constants ---

	const map = new google.maps.Map(document.getElementById("map"), {
		zoom: INITIAL_ZOOM,
		center: INITIAL_CENTER,
		mapId: "4049b264513558e3",
		minZoom: INITIAL_ZOOM - MIN_ZOOM_OFFSET,
		maxZoom: INITIAL_ZOOM + MAX_ZOOM_OFFSET,
		colorScheme: ColorScheme.DARK,
		mapTypeControl: false,
		fullscreenControl: false,
	})

	// Get header height and apply map padding
	const headerElement = document.querySelector('.banner-header-map'); // Use the correct selector for your header
	const fallbackHeaderHeight = 60; // Use a fallback height
	let headerHeight = fallbackHeaderHeight;
	if (headerElement) {
		// Use ResizeObserver to handle dynamic header height changes (optional but robust)
		const resizeObserver = new ResizeObserver(entries => {
			for (let entry of entries) {
				const currentHeight = entry.contentRect.height;
				if (currentHeight !== headerHeight) {
					headerHeight = currentHeight;
					map.setOptions({ padding: { top: headerHeight, bottom: 0, left: 0, right: 0 } });
					console.log('Setting map top padding (observer):', headerHeight); // Log observer update
					// Optional: Adjust fitBounds padding too if needed dynamically
				}
			}
		});
		resizeObserver.observe(headerElement);
		headerHeight = headerElement.offsetHeight; // Set initial height
	} else {
		console.warn('Map header element not found, using fallback height for padding.');
	}
	map.setOptions({ padding: { top: headerHeight, bottom: 0, left: 0, right: 0 } });
	console.log('Setting map top padding (initial):', headerHeight); // Log initial set

	// --- START: Panel References and Logic ---
	const detailsPanel = document.getElementById('porch-details-display');
	const panelContent = detailsPanel?.querySelector('.panel-content');
	const panelFooter = detailsPanel?.querySelector('.panel-footer');
	const panelCloseBtn = detailsPanel?.querySelector('.close-panel-btn');

	function showPanel() {
		if (detailsPanel) detailsPanel.classList.add('is-visible');
	}

	function hidePanel() {
		if (detailsPanel) detailsPanel.classList.remove('is-visible');
	}

	panelCloseBtn?.addEventListener('click', hidePanel);

	function setActiveView(viewId) {
		if (!panelContent) return;
		panelContent.querySelectorAll('.panel-view').forEach(view => {
			view.classList.toggle('is-active', view.id === viewId);
		});
		panelFooter?.querySelectorAll('.panel-button').forEach(button => {
			button.classList.toggle('is-active', button.dataset.viewTarget === viewId);
		});
	}
	// --- END: Panel References and Logic ---

	// --- START: Helper Functions --- 
	/**
	 * Converts time string (e.g., "2:00 pm") to 24-hour format string "HHMM" (e.g., "1400").
	 * Returns empty string if format is invalid.
	 */
	function timeTo24HourFormat(timeStr) {
		if (!timeStr) return '';
		const timeLower = timeStr.toLowerCase().trim();
		const match = timeLower.match(/^(\d{1,2}):(\d{2})\s*(am|pm)$/);
		if (!match) return ''; // Invalid format

		let hours = parseInt(match[1], 10);
		const minutes = parseInt(match[2], 10);
		const modifier = match[3];

		if (isNaN(hours) || isNaN(minutes) || hours < 1 || hours > 12 || minutes < 0 || minutes > 59) {
			return ''; // Invalid time values
		}

		if (modifier === 'pm' && hours !== 12) {
			hours += 12;
		} else if (modifier === 'am' && hours === 12) {
			hours = 0; // Midnight case
		}

		const hoursStr = hours.toString().padStart(2, '0');
		const minutesStr = minutes.toString().padStart(2, '0');
		
		return `${hoursStr}${minutesStr}`;
	}
	// --- END: Helper Functions ---

	const trolleyPath = new google.maps.Polyline({
		path: [
			{lat: 36.76499756442535, 		lng: -119.79898676073246},
			{lat: 36.76493481691089, 		lng: -119.7989826567562},
			{lat: 36.76495642400253, 		lng: -119.79634088202028},
			{lat: 36.76508606642457, 		lng: -119.79633491954776},
			{lat: 36.76505957380197, 		lng: -119.79898258411826},
			{lat: 36.76499756442535, 		lng: -119.79898676073246},
			{lat: 36.76497392052814, 		lng: -119.8010560470801},
			{lat: 36.757276833053425, 	lng: -119.80104330058255},
			{lat: 36.75695599895826, 		lng: -119.80094172815676},
			{lat: 36.75673524935291, 		lng: -119.80074325330153},
			{lat: 36.75653040063636, 		lng: -119.80046655600141},
			{lat: 36.7562301419388, 		lng: -119.800289096131},
			{lat: 36.7504125518585, 		lng: -119.80036587536627},
			{lat: 36.75039926645446, 		lng: -119.80285847982687},
			{lat: 36.75063812285461, 		lng: -119.80283660334874},
			{lat: 36.75765247439101, 		lng: -119.802858812864},
			{lat: 36.7576463078158, 		lng: -119.80575299684742},
			{lat: 36.762494940188596, 	lng: -119.80578027260596},
			{lat: 36.762508871267, 			lng: -119.80456208103357},
			{lat: 36.76498699846067, 		lng: -119.80457387882751},
			{lat: 36.76498300280249, 		lng: -119.80410004432838},
			{lat: 36.7684952509903, 		lng: -119.80411777845197},
			{lat: 36.768521259690694, 	lng: -119.80105965926045},
			{lat: 36.76497392052814, 		lng: -119.8010560470801},
		],
		geodesic: true,
		strokeColor: "#FFA500",
		strokeOpacity: 0.7,
		strokeWeight: 12
	})
	
	/**
	 * Defines all marker categories and their associated label/icon.
	 * Source of truth for:
	 *  - Legend items generated by `buildLegend()`.
	 *  - Marker icon paths assigned in `buildMarkers()`.
	 */
	const markerTypes = {
		'default': { label: 'Porch', icon: `${wpVars.themeURL}/img/map/glyph.svg` },
		'sponsored': { label: 'Sponsor', icon: `${wpVars.themeURL}/img/map/glyph-sponsor.svg` },
		'porta': { label: 'Restroom', icon: `${wpVars.themeURL}/img/map/glyph-porta.svg` },
		'info': { label: 'Info Booth', icon: `${wpVars.themeURL}/img/map/glyph-info.svg` },
		'parking': { label: 'Parking', icon: `${wpVars.themeURL}/img/map/glyph-parking.svg` },
		'vendor': { label: 'Vendor', icon: `${wpVars.themeURL}/img/map/glyph-vendor.svg` },
		'sponsored_vendor': { label: 'Sponsor & Vendor', icon: `${wpVars.themeURL}/img/map/glyph-vendor-sponsor.svg` }
	};

	let allMarkers = []
	let markerCluster

	/* --- START: Dynamic Legend --- */
	/**
	 * Populates the #map-legend element by generating list items based on
	 * the `markerTypes` object.
	 */
	function buildLegend() {
		const legendDiv = document.getElementById("map-legend");
		if (!legendDiv) return;

		const legendContent = document.createElement('div');
		legendContent.id = 'legend-content';
		const legendList = document.createElement('ul');

		Object.entries(markerTypes).forEach(([type, details]) => {
			if(type === 'sponsored_vendor')return
			const listItem = document.createElement('li');
			listItem.dataset.filterType = type;
			listItem.classList.add('is-active');

			const iconImg = document.createElement('img');
			iconImg.src = details.icon;
			iconImg.alt = details.label;
			iconImg.classList.add('legend-icon');

			const labelSpan = document.createElement('span');
			labelSpan.textContent = details.label;
			labelSpan.classList.add('legend-label');

			listItem.appendChild(iconImg);
			listItem.appendChild(labelSpan);
			legendList.appendChild(listItem);
		});

		legendContent.appendChild(legendList);
		legendDiv.innerHTML = '';
		legendDiv.appendChild(legendContent);
	}
	/* --- END: Dynamic Legend --- */

	async function buildMarkers(formData) {
		let fPorches = []
		if (markerCluster) markerCluster.clearMarkers()
		allMarkers.forEach(marker => marker.marker.setMap(null))
		if (formData) {
			if(formData.show_bus){
				trolleyPath.setMap(map)
			} else if (trolleyPath) {
				trolleyPath.setMap(null)
			}
			if (formData.search) {
				const filterPerformers = wpVars.porches.filter(porch => {
					let performermatch = false
					porch.performers.map(performer => {
						if (performermatch) return
						const performerTitle = performer.performer.post_title?.toLowerCase()
						if (performerTitle) {
							if (performerTitle.includes(formData.search)) {
								performermatch = true
								if (!matches.includes(performer.performer.post_title)) {
									matches.push(performer.performer.post_title)
								}
							}
						}
					})
					return performermatch
				})
				const searched = wpVars.porches.filter(porch => formData.wp_search.some(searchPorch => porch.porch.ID === searchPorch.id))
				const combined = [...filterPerformers, ...searched]
				const deduped = Array.from(
					new Map(combined.map(porch => [porch.porch.ID, porch])).values()
				)
				fPorches = filterData(deduped, formData)
			} else {
				fPorches = filterData(wpVars.porches, formData)
			}
		} else {
			fPorches = wpVars.porches
			trolleyPath.setMap(null)
		}

		// else if(window.location.hash){
		// 	fPorches = filterData(wpVars.porches, {search: [window.location.hash.replace("#", "")]})
		// }
		const seenCoords = {}
		allMarkers = fPorches.map((porch, i) => {
			let lat = parseFloat(porch.acf.latitude)
			let lng = parseFloat(porch.acf.longitude)
			const key = `${lat.toFixed(5)},${lng.toFixed(5)}`
			if (seenCoords[key]) {
				const offset = MARKER_OFFSET_FACTOR * seenCoords[key];
				lat += Math.cos(i) * offset
				lng += Math.sin(i) * offset
				seenCoords[key]++
			} else {
				seenCoords[key] = 1
			}

			// Determine marker type string based on ACF fields
			let markerType = 'default'; // Start with default
			if (porch.acf.sponsored && porch.acf.has_food) {
				markerType = 'sponsored_vendor'
			} else if(porch.acf.sponsored){
				markerType = 'sponsored';
			} else if (porch.acf.porta_potty) {
				markerType = 'porta';
			} else if (porch.acf.info_booth) {
				markerType = 'info';
			} else if (porch.acf.parking) {
				markerType = 'parking';
			} else if (porch.acf.has_food) {
				markerType = 'vendor';
			}

			// Assign icon using the determined type and the markerTypes object
			const glyph = document.createElement("img");
			glyph.src = markerTypes[markerType].icon;

			// Assign zIndex based on type (can be expanded if needed)
			let zIndex = 1000;
			if (markerType === 'porta') {
				zIndex = 30000;
			}

			glyph.style.height = "40px";
			const marker = new google.maps.marker.AdvancedMarkerElement({
				map,
				position: { lat, lng },
				content: glyph,
				zIndex,
			})

			// --- Generate Panel Content --- 
			const imgurl = porch.img ? porch.img : DEFAULT_FALLBACK_IMAGE_URL;
			let lineupHTML = ``;
			if (porch.acf.performer_lineup) {
				let tableRows = '';
				porch.acf.performer_lineup.forEach((performer) => {
					let highlightStyle = matches.includes(performer.performer.post_title) ? ' style="background-color: #ffb6c1"' : ''; // Keep highlight logic if needed
					
					// --- Generate Planner URL ---
					let planUrl = '#'; // Default if data missing
					const performerSlug = performer.performer?.post_name;
					const porchSlug = porch.porch?.post_name;
					const startTime24h = timeTo24HourFormat(performer.start_time);

					if (performerSlug && porchSlug && startTime24h) {
						const viewSlug = `${performerSlug}-at-${porchSlug}-${startTime24h}`;
						const callbackUrl = `${window.location.origin}/map/#porch-${porchSlug}`;
						const encodedCallbackUrl = encodeURIComponent(callbackUrl);
						planUrl = `https://plan.towerporchfest.org/?viewSlugs=${viewSlug}&callbackUrl=${encodedCallbackUrl}`;
					} else {
						console.warn('Missing data for planner link:', { performerSlug, porchSlug, startTime: performer.start_time });
					}
					// --- End Generate Planner URL ---

					tableRows += `<tr>
						<td>${performer.start_time}</td>
						<td${highlightStyle}><a href="/performer/${performer.performer.post_name}">${performer.performer.post_title}</a></td>
						<td><a href="${planUrl}" class="add-to-itinerary-btn" target="_blank" title="Add to Plan Your Day">+</a></td>
					</tr>`;
				});
				if (porch.acf.has_food && porch.acf.food_vendor?.food_name) {
					tableRows += `<tr><td>Vendor</td><td>${porch.acf.food_vendor.food_name}</td><td></td></tr>`; // Add empty cell for consistency
				}
				if(tableRows) {
					lineupHTML = `<div class="lineup"><table class="lineup-table"><thead><tr><th>Start Time</th><th>Performer</th><th>Add to Itinerary</th></tr></thead><tbody>${tableRows}</tbody></table></div>`;
				}
			}
			let portaAddress = porch.acf.porta_potty ? `<p>${porch.acf.porch_address}</p>` : '';

			const detailsViewHTML = `
				<div class="panel-view is-active" id="panel-details-view" data-marker-type="${markerType}">
					<h3><a href="${porch.link}" target="_blank">${porch.porch.post_title || 'Porch'}</a></h3> 
					<div class="popup-image-section">
						<img src="${imgurl}" alt="Image for ${porch.porch.post_title || 'Porch'}">
					</div>
					<div class="content">
						${portaAddress}
						<p>${porch.porch.post_content || ''}</p>
					</div>
				</div>
			`;

			const lineupViewHTML = `
				<div class="panel-view" id="panel-lineup-view" data-marker-type="${markerType}">
					${lineupHTML || '<p>No lineup information available.</p>'}
				</div>
			`;

			// --- Generate Footer Buttons (Conditionally) --- 
			let lineupButtonHTML = '';
			// Check if lineup data exists and is not empty
			if (porch.acf.performer_lineup && porch.acf.performer_lineup.length > 0) {
				lineupButtonHTML = '<button class="panel-button" data-view-target="panel-lineup-view">Lineup</button>';
			}

			const footerButtonsHTML = `
				<button class="panel-button is-active" data-view-target="panel-details-view">Details</button>
				${lineupButtonHTML} 
				<a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=walking" target="_blank" class="panel-button directions-button">Directions</a>
			`;
			// --- End Generate Panel Content ---

			marker.addListener("gmp-click", () => {
				if (!detailsPanel || !panelContent || !panelFooter) return; // Safety check

				// Set panel theme based on marker type
				detailsPanel.dataset.markerType = markerType;

				// Inject content
				panelContent.innerHTML = detailsViewHTML + lineupViewHTML;
				panelFooter.innerHTML = footerButtonsHTML;

				// Add listeners to NEW footer buttons
				panelFooter.querySelectorAll('.panel-button[data-view-target]').forEach(button => {
					button.addEventListener('click', (e) => {
						setActiveView(e.target.dataset.viewTarget);
					});
				});

				// Show panel
				showPanel();

				// Pan map - Center marker vertically between header and panel
				// Needs a slight delay for offsetHeight to be accurate after panel appears
				setTimeout(() => {
					const panelHeight = detailsPanel.offsetHeight;
					if (panelHeight > 0) {
						map.panTo({ lat, lng }); // Center horizontally first
						map.panBy(0, panelHeight / 2); // Then pan up by half panel height
					} else {
						// Fallback if height isn't read correctly immediately
						map.panTo({ lat, lng }); 
					}
				}, 50); // 50ms delay, adjust if needed
			})
			return {
				marker,
				clusterable: !porch.acf.info_booth && !porch.acf.porta_potty && !porch.acf.parking
			}
		})

		const trolleyStops = [
			{lat: 36.76519733738659, 		lng: -119.79805882890179},
			{lat: 36.76469739172577, 		lng: -119.80134302791159},
			{lat: 36.762119306178015, 	lng: -119.80130066665852},
			{lat: 36.75812094163349, 		lng: -119.80132113053851},
			{lat: 36.754195136917694, 	lng: -119.80061573212695},
			{lat: 36.750676988342896, 	lng: -119.80063996251968},
			{lat: 36.7579521229005, 		lng: -119.8054374422218},
			{lat: 36.762504287017435, 	lng: -119.80424099454666},
			{lat: 36.766280784439516, 	lng: -119.80377414029049},
			{lat: 36.76821008036845, 		lng: -119.80384808424272}
		]
		trolleyMarkers = trolleyStops.map(stop=>{
			const trolleyGlyph				= document.createElement("img");
			trolleyGlyph.src 					= `${wpVars.themeURL}/img/map/glyph-bus.svg`
			trolleyGlyph.style.height = "25px"
			const trolleyMarker = new google.maps.marker.AdvancedMarkerElement({
				map,
				position: stop,
				content: trolleyGlyph,
			})
			return trolleyMarker
		})

		const clusteredMarkers = allMarkers.filter(m => m.clusterable).map(m => m.marker)
		const nonClusteredMarkers = allMarkers.filter(m => !m.clusterable).map(m => m.marker)

		const renderer = {
			render: ({ count, position }) => {
				const div = document.createElement("div");
				div.className = "custom-cluster";
				div.textContent = count;
			
				return new google.maps.marker.AdvancedMarkerElement({
					position,
					content: div,
					zIndex: 1000 + count,
				});
			}
		};
		markerCluster = new markerClusterer.MarkerClusterer({
			markers: clusteredMarkers,
			map,
			// renderer,
		})
	}
	buildMarkers()
	buildLegend()

	map.addListener("click", () => {
		hidePanel(); // Hide panel when clicking map background
	})

	const form = document.getElementById("map_filter")
	const search = document.getElementById("filter_search")

	form.addEventListener("submit", (e) => {
		e.preventDefault()
		matches = []
		const formData = new FormData(form)
		const values = Object.fromEntries(formData.entries())
		if (values.search) {
			fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?search=${search.value}`)
				.then(response => response.json())
				.then(data => {
					values["wp_search"] = data
					buildMarkers(values)
				})
		} else {
			buildMarkers(values)
		}
		document.getElementById("map_menu").style.display = "none"
	})
	document.getElementById("reset_filter").addEventListener("click", () => {
		form.reset()
		matches = []
		buildMarkers()
		document.getElementById("map_menu").style.display = "none"
	})
}

function filterData(data, formData) {
	hasLineup = data.filter(porch => porch.acf.performer_lineup)
	if (formData.sponsor) {
		data = data.filter(porch => porch.acf.sponsored)
	}
	if (formData.vendor) {
		data = data.filter(porch => porch.acf.has_food)
	}
	if (formData.porta) {
		data = data.filter(porch => porch.acf.porta_potty)
	}
	if(formData.now_time){
		let playingNow = []
		const rightNow = new Date()
		// rightNow.setHours(17, 0, 0, 0) // Set to 2:00 PM for testing
		const THIRTY_MINUTES = 30 * 60 * 1000
		const thirtyOutDate = new Date(rightNow.getTime() + THIRTY_MINUTES)

		if (hasLineup.length != 0) {
			hasLineup.forEach(porch => {
				let bool = false
					porch.acf.performer_lineup.forEach(performer => {
						if (bool) return
						const [startTime, startModifier] = performer.start_time.trim().split(" ");
						[startHours, startMinutes] = startTime.split(":").map(Number)
						if (startModifier === "pm" && startHours !== 12) startHours += 12
						if (startModifier === "am" && startHours === 12) startHours = 0
						const starts = new Date(rightNow.getFullYear(), rightNow.getMonth(), rightNow.getDate(), startHours, startMinutes, 0, 0)
						const [endTime, endModifier] = performer.end_time.trim().split(" ");
						[endHours, endMinutes] = endTime.split(":").map(Number)
						if (endModifier === "pm" && endHours !== 12) endHours += 12
						if (endModifier === "am" && endHours === 12) endHours = 0
						const ends = new Date(rightNow.getFullYear(), rightNow.getMonth(), rightNow.getDate(), endHours, endMinutes, 0, 0)
						const isPlayingNow = (ends >= rightNow && ends <= thirtyOutDate) || (starts >= rightNow && starts <= thirtyOutDate)
						if(isPlayingNow){
							bool = true
							if (!matches.includes(performer.performer.post_title)) {
								matches.push(performer.performer.post_title)
							}
						}
					})
					if (bool) {
						playingNow.push(porch)
					}
				})
			data = playingNow
		}
	}
	if (formData.time) {
		let afterTime = []
		if (hasLineup.length != 0) {
			hasLineup.forEach(porch => {
				let bool = false
				porch.acf.performer_lineup.forEach(performer => {
					if (bool) return
					let [hours, minutes] = formData.time.split(':').map(Number)
					const now = new Date()
					const formDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0, 0)
					const [time, modifier] = performer.start_time.trim().split(" ");
					[hours, minutes] = time.split(":").map(Number)
					if (modifier === "pm" && hours !== 12) hours += 12
					if (modifier === "am" && hours === 12) hours = 0
					const performanceDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0, 0)
					if (formDate <= performanceDate) {
						if (!matches.includes(performer.performer.post_title)) {
							matches.push(performer.performer.post_title)
						}
						bool = true
					}
				})
				if (bool) {
					afterTime.push(porch)
				}
			})
			data = afterTime
		}
	}
	if (formData.genre != "none") {
		let hasGenre = []
		if (hasLineup.length != 0) {
			hasLineup.forEach(porch => {
				let bool = false
				porch.performers.forEach(performer => {
					if (bool) return
					if (performer.genres) {
						if (performer.genres.filter(genre => genre == formData.genre).length != 0) {
							if (!matches.includes(performer.performer.post_title)) {
								matches.push(performer.performer.post_title)
							}
							bool = true
						}
					}
				})
				if (bool) {
					hasGenre.push(porch)
				}
			})
			data = hasGenre
		}
	}
	return data
}

const menu = document.getElementById("map_menu")
document.getElementById("map_menu_btn").addEventListener("click", () => {
	menu.style.display = "block"
})
document.getElementById("close_menu").addEventListener("click", () => {
	menu.style.display = "none"
})

window.addEventListener('resize', setVhUnit)

function setVhUnit() {
	const vh = window.innerHeight * 0.01;
	document.documentElement.style.setProperty('--vh', `${vh}px`)
}
setVhUnit()
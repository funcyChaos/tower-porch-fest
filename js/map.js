let matches = []

async function initMap(){
	const {ColorScheme} 				= await google.maps.importLibrary("core")
	const zoom = 14.2
	const map = new google.maps.Map(document.getElementById("map"), {
    zoom,
    center: {lat: 36.7650533, lng: -119.7995578},
    mapId: "4049b264513558e3",
		minZoom: zoom - 2,
    maxZoom: zoom + 3,
		colorScheme:							ColorScheme.DARK,
		mapTypeControl: 					false,
		fullscreenControl:				false,
  })
	class Popup extends google.maps.OverlayView {
    position
    containerDiv
    constructor(position, content){
      super()
      content.classList.add("popup-bubble")

      // This zero-height div is positioned at the top of the bubble.
      const bubbleAnchor = document.createElement("div")

      bubbleAnchor.classList.add("popup-bubble-anchor")
      bubbleAnchor.appendChild(content)
      // This zero-height div is positioned at the bottom of the tip.
      this.containerDiv = document.createElement("div")
      this.containerDiv.classList.add("popup-container")
      this.containerDiv.appendChild(bubbleAnchor)
      // Optionally stop clicks, etc., from bubbling up to the map.
      Popup.preventMapHitsAndGesturesFrom(this.containerDiv)
    }
    /** Called when the popup is added to the map. */
    onAdd(){
      this.getPanes().floatPane.appendChild(this.containerDiv)
    }
    /** Called when the popup is removed from the map. */
    onRemove(){
      if (this.containerDiv.parentElement){
        this.containerDiv.parentElement.removeChild(this.containerDiv)
      }
    }
    /** Called each frame when the popup needs to draw itself. */
    draw() {
      const divPosition = this.getProjection().fromLatLngToDivPixel(
        this.position,
      )
      // Hide the popup when it is far out of view.
      const display =
        Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000
          ? "block"
          : "none"
      if(display === "block"){
        this.containerDiv.style.left = divPosition.x + "px"
        this.containerDiv.style.top = divPosition.y + "px"
      }
      if(this.containerDiv.style.display !== display){
        this.containerDiv.style.display = display;
      }
    }
  }

	const contentDiv = document.createElement("div");
	contentDiv.id = "content";

  const popup = new Popup(
    new google.maps.LatLng(-33.866, 151.196),
		contentDiv,
  )

	let allMarkers = []
	let markerCluster
	async function buildMarkers(formData){
		let fPorches = []
		if(markerCluster)markerCluster.clearMarkers()
		popup.setMap(null)
		allMarkers.forEach(marker=>marker.marker.setMap(null))
		if(formData){
			if(formData.search){
				const filterPerformers = wpVars.porches.filter(porch=>{
					let performermatch = false
					porch.performers.map(performer=>{
						if(performermatch)return
						const performerTitle = performer.performer.post_title?.toLowerCase()
						if(performerTitle){
							if(performerTitle.includes(formData.search)){
								performermatch = true
								if(!matches.includes(performer.performer.post_title)){
									matches.push(performer.performer.post_title)
								}
							}
						}
					})
					return performermatch
				})
				const searched = wpVars.porches.filter(porch=>formData.wp_search.some(searchPorch=>porch.porch.ID === searchPorch.id))
				const combined = [...filterPerformers, ...searched]
				const deduped = Array.from(
					new Map(combined.map(porch => [porch.porch.ID, porch])).values()
				)
				fPorches = filterData(deduped, formData)
			}else{
				fPorches = filterData(wpVars.porches, formData)
			}
		}else{
			fPorches = wpVars.porches
		}

		// else if(window.location.hash){
		// 	fPorches = filterData(wpVars.porches, {search: [window.location.hash.replace("#", "")]})
		// }
		const seenCoords = {}
		allMarkers = fPorches.map((porch, i)=>{
			let lat = parseFloat(porch.acf.latitude)
			let lng = parseFloat(porch.acf.longitude)
			const glyph = document.createElement("img")
			const key = `${lat.toFixed(5)},${lng.toFixed(5)}`
			if(seenCoords[key]){
				const offset = 0.0002 * seenCoords[key]
				lat += Math.cos(i) * offset
				lng += Math.sin(i) * offset
				seenCoords[key]++
			}else{
				seenCoords[key] = 1
			}

			glyph.src = `${wpVars.themeURL}/img/map/glyph.svg`
			let zIndex = 1000
			if(porch.acf.sponsored){
				glyph.src = `${wpVars.themeURL}/img/map/glyph-sponsor.svg`
			}else if(porch.acf.porta_potty){
				glyph.src = `${wpVars.themeURL}/img/map/glyph-porta.svg`
				zIndex = 30000
			}else if(porch.acf.info_booth){
				glyph.src = `${wpVars.themeURL}/img/map/glyph-info.svg`
			}else if(porch.acf.parking){
				glyph.src = `${wpVars.themeURL}/img/map/glyph-parking.svg`
			}
			glyph.style.height = "40px";
			const marker = new google.maps.marker.AdvancedMarkerElement({
				map,
				position: {lat, lng},
				content: glyph,
				zIndex,
			})
			const imgurl = porch.img ? porch.img : "https://towerporchfest.org/wp-content/uploads/2025/01/Untitled-1803-x-670-px1.png"
	
			let lineup = ``
			if(porch.acf.performer_lineup){
				porch.acf.performer_lineup.forEach((performer, i)=>{
					let highlight = "initial"
					if(matches.includes(performer.performer.post_title)){
						highlight = "#ffb6c1"
					}
					lineup += `<tr><td>${performer.start_time}</td><td style="background-color: ${highlight}"><a href="/performer/${performer.performer.post_name}">${performer.performer.post_title}</a></td></tr>`
				})
			}
			if(porch.acf.has_food){
				lineup += `<tr><td>Vendor</td><td>${porch.acf.food_vendor.food_name}</td></tr>`
			}
			if(lineup){
				lineup = `<div class="lineup"><table class="lineup-table"><tbody><tr><th>START TIME</th><th>PERFORMER</th></tr>${lineup}</tbody></table></div>`
			}
	
			marker.addListener("gmp-click", ()=>{
				popup.position = new google.maps.LatLng(lat, lng)
				contentDiv.innerHTML = `<div class="inner-container"><a href="${porch.link}"><h3>${porch.porch.post_title}</h3></a><img src="${imgurl}" alt="Default"><div class="header"><a href="https://www.google.com/maps/dir/?api=1&destination=${lat},${lng}&travelmode=walking" target="_blank"><button>Get Directions!</button><a/></div>${lineup}<div class="content"><p>${porch.porch.post_content}</p></div></div>`
				const close = document.createElement("i")
				close.classList.add("fas", "fa-times-circle", "popup-close")
				contentDiv.appendChild(close)
				popup.setMap(map)
				close.addEventListener("click", ()=>{
					popup.setMap(null)
				})
				const newCenter = {
					lat: popup.position.lat() + 300 / Math.pow(2, map.getZoom()),
					lng: popup.position.lng(),
				}
				map.panTo(newCenter)
			})
			return {
				marker,
				clusterable: !porch.acf.info_booth && !porch.acf.porta_potty
			}
		})
		const clusteredMarkers = allMarkers.filter(m => m.clusterable).map(m => m.marker)
		const nonClusteredMarkers = allMarkers.filter(m => !m.clusterable).map(m => m.marker)
		markerCluster = new markerClusterer.MarkerClusterer({
			markers: clusteredMarkers,
			map
		})
	}
	buildMarkers()
	
	map.addListener("click", ()=>{
		popup.setMap(null)
	})

	const form		= document.getElementById("map_filter")
	const search = document.getElementById("filter_search")

	form.addEventListener("submit", (e)=>{
		e.preventDefault()
		matches = []
		const formData = new FormData(form)
		const values = Object.fromEntries(formData.entries())
		if(values.search){
			fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?search=${search.value}`)
			.then(response=>response.json())
			.then(data=>{
				values["wp_search"] = data
				buildMarkers(values)
			})
		}else{
			buildMarkers(values)
		}
		document.getElementById("map_menu").style.display = "none"
	})
	document.getElementById("reset_filter").addEventListener("click", ()=>{
		form.reset()
		matches = []
		buildMarkers()
		document.getElementById("map_menu").style.display = "none"
	})
}

function filterData(data, formData){
	hasLineup = data.filter(porch=>porch.acf.performer_lineup)
	if(formData.sponsor){
		data = data.filter(porch=>porch.acf.sponsored)
	}
	if(formData.vendor){
		data = data.filter(porch=>porch.acf.has_food)
	}
	if(formData.porta){
		data = data.filter(porch=>porch.acf.porta_potty)
	}
	if(formData.time){
		let afterTime = []
		if(hasLineup.length != 0){
			hasLineup.forEach(porch=>{
				let bool = false
				if(porch.porch.ID == 983){
					console.log(porch)
				}
				porch.acf.performer_lineup.forEach(performer=>{
					if(bool) return
					let [hours, minutes] = formData.time.split(':').map(Number)
					const now = new Date()
					const formDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0, 0)
					const [time, modifier] = performer.start_time.trim().split(" ");
					[hours, minutes] = time.split(":").map(Number)
					if (modifier === "pm" && hours !== 12) hours += 12
					if (modifier === "am" && hours === 12) hours = 0
					const performanceDate = new Date(now.getFullYear(), now.getMonth(), now.getDate(), hours, minutes, 0, 0)
					if(formDate <= performanceDate){
						bool = true
					}
				})
				if(bool){
					afterTime.push(porch)
				}
			})
			data = afterTime
		}
	}
	if(formData.genre != "none"){
		let hasGenre = []
		if(hasLineup.length != 0){
			hasLineup.forEach(porch=>{
				let bool = false
				porch.performers.forEach(performer=>{
					if(bool) return
					if(performer.genres){
						if(performer.genres.filter(genre=>genre == formData.genre).length != 0){
							if(!matches.includes(performer.performer.post_title)){
								matches.push(performer.performer.post_title)
							}
							bool = true
						}
					}
				})
				if(bool){
					hasGenre.push(porch)
				}
			})
			data = hasGenre
		}
	}
	return data
}

const menu = document.getElementById("map_menu")
document.getElementById("map_menu_btn").addEventListener("click", ()=>{
	menu.style.display = "block"
})
document.getElementById("close_menu").addEventListener("click", ()=>{
	menu.style.display = "none"
})

window.addEventListener('resize', setVhUnit)

function setVhUnit(){
  const vh = window.innerHeight * 0.01;
  document.documentElement.style.setProperty('--vh', `${vh}px`)
}
setVhUnit()
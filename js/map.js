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
      const bubbleAnchor = document.createElement("div");

      bubbleAnchor.classList.add("popup-bubble-anchor");
      bubbleAnchor.appendChild(content);
      // This zero-height div is positioned at the bottom of the tip.
      this.containerDiv = document.createElement("div");
      this.containerDiv.classList.add("popup-container");
      this.containerDiv.appendChild(bubbleAnchor);
      // Optionally stop clicks, etc., from bubbling up to the map.
      Popup.preventMapHitsAndGesturesFrom(this.containerDiv);
    }
    /** Called when the popup is added to the map. */
    onAdd(){
      this.getPanes().floatPane.appendChild(this.containerDiv);
    }
    /** Called when the popup is removed from the map. */
    onRemove(){
      if (this.containerDiv.parentElement) {
        this.containerDiv.parentElement.removeChild(this.containerDiv);
      }
    }
    /** Called each frame when the popup needs to draw itself. */
    draw() {
      const divPosition = this.getProjection().fromLatLngToDivPixel(
        this.position,
      );
      // Hide the popup when it is far out of view.
      const display =
        Math.abs(divPosition.x) < 4000 && Math.abs(divPosition.y) < 4000
          ? "block"
          : "none";

      if(display === "block"){
        this.containerDiv.style.left = divPosition.x + "px";
        this.containerDiv.style.top = divPosition.y + "px";
      }

      if(this.containerDiv.style.display !== display){
        this.containerDiv.style.display = display;
      }
    }
  }

	const contentDiv = document.createElement("div");
	contentDiv.id = "content";

  popup = new Popup(
    new google.maps.LatLng(-33.866, 151.196),
		contentDiv,
  )

	let markers = []
	// let markerClusterer
	async function buildMarkers(formData){
		let fPorches = []
		if(formData){

			if(formData.search){
				const searched = wpVars.porches.filter(porch=>formData.search.some(searchPorch=>porch.porch.ID === searchPorch.id))
				fPorches = filterData(searched, formData)

			}else{

				fPorches = filterData(wpVars.porches, formData)
			}
			markers.forEach(marker=>marker.setMap(null))
			// markerCluster.clearMarkers()

			// return
		}else{
			fPorches = wpVars.porches
		}

		markers = fPorches.map((porch, i)=>{
			const lat = parseFloat(porch.acf.latitude)
			const lng = parseFloat(porch.acf.longitude)
			const glyph = document.createElement("img")
			if(porch.acf.sponsored){
				glyph.src = `${wpVars.themeURL}/img/map/glyph-sponsor.svg`
			}else{
				glyph.src = `${wpVars.themeURL}/img/map/glyph.svg`
			}
			glyph.style.height = "40px";
			const marker = new google.maps.marker.AdvancedMarkerElement({
				map,
				position: {lat, lng},
				content: glyph,
			})
			const imgurl = porch.img ? porch.img : "https://towerporchfest.org/wp-content/uploads/2025/01/Untitled-1803-x-670-px1.png"
	
			// markers can only be keyboard focusable when they have click listeners
			// open info window when marker is clicked
	
			let lineup = ``
			if(porch.acf.performer_lineup){
				porch.acf.performer_lineup.forEach((performer, i)=>{
					lineup += `<tr><td>${performer.start_time}</td><td>${performer.performer.post_title}</td></tr>`
				})
			}
	
			marker.addListener("gmp-click", ()=>{
				popup.position = new google.maps.LatLng(lat, lng)
				contentDiv.innerHTML = `<div id="content"><h3>${porch.porch.post_title}</h3><img src="${imgurl}" alt="Default"><div class="header"><p>${porch.acf.porch_address}</p></div><div class="content"><p>${porch.porch.post_content}</p></div><div class="lineup"><table class="lineup-table"><tbody><tr><th>START TIME</th><th>PERFORMER</th></tr>${lineup}</tbody></table></div></div>`
				popup.setMap(map)
				const newCenter = {
					lat: popup.position.lat() + 300 / Math.pow(2, map.getZoom()),
					lng: popup.position.lng(),
				}
				map.panTo(newCenter)
			})
			return marker
		})
		// markerCluster = new markerClusterer.MarkerClusterer({markers, map})
	}
	buildMarkers()

  // Add a marker clusterer to manage the markers.
  // new MarkerClusterer({markers, map})
	
	map.addListener("click", ()=>{
		popup.setMap(null)
	})


	const form		= document.getElementById("map_filter")
	const search = document.getElementById("filter_search")

	form.addEventListener("submit", (e)=>{
		e.preventDefault()
		const formData = new FormData(form)
		const values = Object.fromEntries(formData.entries())
		if(values.search){
			fetch(`${wpVars.homeURL}/wp-json/wp/v2/porches?search=${search.value}`)
			.then(response=>response.json())
			.then(data=>{
				values.search = data
				buildMarkers(values)
			})
		}else{
			buildMarkers(values)
		}
		document.getElementById("map_menu").style.display = "none"
	})
}

function filterData(data, formData){
	data = data.filter(porch=>porch.acf.performer_lineup)
	if(formData.vendor){
		data = data.filter(porch=>porch.acf.sponsored)
	}
	if(formData.porta){
		data = data.filter(porch=>porch.acf.porta_potty)
	}
	if(formData.time){
		let afterTime = []
		data.forEach(porch=>{
			let bool = false
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
	if(formData.genre != "none"){
		let hasGenre = []
		data.forEach(porch=>{
			let bool = false
			porch.performers.forEach(performer=>{
				if(bool) return
				if(performer.genres){
					if(performer.genres.filter(genre=>genre == formData.genre).length != 0){
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
	return data
}

const menu = document.getElementById("map_menu")
document.getElementById("map_menu_btn").addEventListener("click", ()=>{
	menu.style.display = "block"
})
document.getElementById("close_menu").addEventListener("click", ()=>{
	menu.style.display = "none"
})
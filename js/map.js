async function initMap(){
	const zoom = 14.2
	const map = new google.maps.Map(document.getElementById("map"), {
    zoom,
    center: {lat: 36.7650533, lng: -119.7995578},
    mapId: "4049b264513558e3",
		minZoom: zoom - 2,
    maxZoom: zoom + 3,
  })
	class Popup extends google.maps.OverlayView {
    position
    containerDiv
    constructor(position, content) {
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
    onAdd() {
      this.getPanes().floatPane.appendChild(this.containerDiv);
    }
    /** Called when the popup is removed from the map. */
    onRemove() {
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

      if (display === "block") {
        this.containerDiv.style.left = divPosition.x + "px";
        this.containerDiv.style.top = divPosition.y + "px";
      }

      if (this.containerDiv.style.display !== display) {
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

  // Create an array of alphabetical characters used to label the markers.
  const labels = "ABCDEFGHIJKLMNOPQRSTUVWXYZ"
  // Add some markers to the map.
  const markers = wpVars.porches.map((porch, i)=>{
    const label = labels[i % labels.length];
    const pinGlyph = new google.maps.marker.PinElement({
      glyph: label,
      glyphColor: "white",
    })
		const lat = parseFloat(porch.acf.latitude)
		const lng = parseFloat(porch.acf.longitude)
    const marker = new google.maps.marker.AdvancedMarkerElement({
      position: {lat, lng},
      content: pinGlyph.element,
    })

    // markers can only be keyboard focusable when they have click listeners
    // open info window when marker is clicked
    marker.addListener("click", ()=>{
			popup.position = new google.maps.LatLng(lat, lng)
			contentDiv.innerHTML = `${lat} ${lng}`
			popup.setMap(map)
    })
    return marker
  })

  // Add a marker clusterer to manage the markers.
  // new MarkerClusterer({markers, map})
	const markerCluster = new markerClusterer.MarkerClusterer({ markers, map })
	map.addListener("click", ()=>{
		popup.setMap(null)
	})
}
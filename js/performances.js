document.addEventListener("DOMContentLoaded", ()=>{
	const rootElement = document.getElementById("react_root")
	if(rootElement){
		const root = ReactDOM.createRoot(rootElement);
		root.render(React.createElement("h1", null, "React Template"));
	}
})
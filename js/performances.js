document.addEventListener("DOMContentLoaded", function () {
	const rootElement = document.getElementById("react-root");
	if (rootElement) {
			const root = ReactDOM.createRoot(rootElement);
			root.render(React.createElement("h1", null, "Hello, World from React!"));
	}
});

document.addEventListener("DOMContentLoaded", ()=>{
	const rootElement = document.getElementById("react_root")
	if(rootElement){
		const root = ReactDOM.createRoot(rootElement);
		root.render(React.createElement("h1", null, "React Template"));
	}
})
document.addEventListener('DOMContentLoaded', ()=>{
	wp.data.dispatch('core/edit-post').removeEditorPanel('featured-image');
})
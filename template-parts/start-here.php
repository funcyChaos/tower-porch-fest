<h1>Greetings Porch Host!</h1>
<!-- <h3>Here are steps you should take to fill out your custom porch page:</h3> -->
<!-- <ol style="font-weight: bolder;">
	<li style="background-color: blue; position: absolute; top:55px;">
		<p style="position: absolute; top:55px;">Click Add Porch! or click here, and completed the form.</p>
	</li>
	<li>
		<p>Create your performers: Click Add Performer in the menu, or click here to Add Performer.</p>
	</li>
	<li>
		<p>Return to your Porch Page and add performers and times.</p>
	</li>
	<li>
		<p>Send an email to towerporchinfo@gmail.com - letting us know you are ready to go live.</p>
	</li>
	<li>
		<p>If things change, come back and update your porch and performers. All porch lineups must be completed by April 1.</p>
	</li>
</ol> -->

<div style="position: absolute; top:55px; font-weight:bolder;" id="porch_arrow">
	&larr; Head <a href="<?=get_site_url();?>/wp-admin/edit.php?post_type=porch">here</a> to see your porch or make a new one!
</div>
<div style="position: absolute; top:89px; font-weight:bolder;" id="pfmrs_arrow">
	&larr; Head <a href="<?=get_site_url();?>/wp-admin/edit.php?post_type=performer">here</a> to make a new performer to add to your porch!
</div>
<div style="position: absolute; top:110px; font-weight:bolder" id="start_here_steps">
	Once your porch is setup and has performers send an email to towerporchinfo@gmail.com - letting us know you are ready to go live.
</div>



<script>
	const porchMenu = document.getElementById('menu-posts-porch').getBoundingClientRect()
	document.getElementById('porch_arrow').style.top = porchMenu.top - 23 + 'px'
	const pfmrsMenu = document.getElementById('menu-posts-performer').getBoundingClientRect()
	document.getElementById('pfmrs_arrow').style.top = pfmrsMenu.top - 23 + 'px'
	const startSteps = document.getElementById('start_here_steps')
	startSteps.style.top = pfmrsMenu.top + 10 + 'px'
</script>
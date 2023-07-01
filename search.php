<?php

	get_header();
	?>
		<div class="search-container">
			<h1>SEARCH PORCHES</h1>
			<?php
				get_search_form();
			?>
			<div class="results-header">
				<h1>RESULTS FOR:</h1>
				<h2><?=get_search_query()?></h2>
			</div>
		</div>
		<?php
			get_template_part('template-parts/list-porches');
			get_footer();
		?>
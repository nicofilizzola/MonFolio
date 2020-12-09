<?php
	require('header.php');
?>

	<section>

		<?php

			// FOR VISITOR - interface.php
			cta_band();
			signin_form();
			signup_form();

			// FOR EVERYONE
			filter_searchbar();

		?>

	<section>
		
	<?php

		// functions.php
		user_info();

	?>

		<div class="project-grid">

			<?php
			
				// functions.php
				home();

			?>

		</div>

	</section>

	<?php

		require('footer.php');

	?>
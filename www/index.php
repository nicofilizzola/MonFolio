<?php
	require('header.php');
?>

	<?php

		// FOR VISITOR - interface.php
		cta_band();
		signin_form();
		signup_form();

		// FOR EVERYONE
		

	?>

		
	<?php

		// functions.php
		user_info();

	?>

	<section class="container">

		<?php
			filter_searchbar();
		?>
		
		<div class="project-grid">

			<?php
			
				// functions.php
				home($cat);

			?>

		</div>

	</section>

	<?php

		require('footer.php');

	?>
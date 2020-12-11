<?php
	require('header.php');
?>

	<span class="errormsg">
		<?php
			errormsg(); // functions.php
		?>
	</span>

	<?php

		// FOR VISITOR - interface.php
		cta_band();
		signin_form();
		signup_form();

		// FOR EVERYONE
		
	?>

	<section class="flex flex--col flex--ai-c">	
		
		<?php
			// functions.php
			user_info();
		?>
		<div class="container">
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
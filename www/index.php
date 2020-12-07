<?php
	require('header.php');

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

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
		home();

	?>

	</section>

	<?php

		require('footer.php');

	?>
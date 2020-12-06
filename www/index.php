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

		home();

	?>

	</section>

	<footer>

	</footer>
	
</body>
</html>
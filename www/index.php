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

			<div>
			
				<div>
					<img src="" alt="">
					<p>Titre projet</p>
				</div>
			
			</div>

	<section>
		
	<?php

		home();

	?>

	</section>
	
	<footer>

	</footer>
	
</body>
</html>
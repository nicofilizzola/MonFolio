<?php

	require('header.php');


	$_GET['user_id'] = $_SESSION['my_user_id'];

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

?>

    <section>

		<?php

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
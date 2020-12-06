<?php

	require('header.php');


	$_GET['user_id'] = $_SESSION['my_user_id'];

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

?>

    <section>

			<div>
			
				<div>
					<img src="" alt="">
					<p>Titre projet</p>
				</div>
			
			</div>

	<section>
		
	<?php

		// EDIT PROJECT INTERFACE - functions.php
		edit_project();


		if(isset($_POST['save_submit'])){

			$file = $_FILES['media'];
			$file_name = $_FILES['media']['name'];

			var_dump($file);
			echo '<br>';
			var_dump($file_name);

		}

	?>

	</section>
	
		
	<footer>

	</footer>
	
</body>
</html>
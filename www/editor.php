<?php

	require('header.php');

	$_GET['user_id'] = $_SESSION['my_user_id'];

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

?>

    <section>


	<section>
		
	<?php

		// EDIT PROJECT INTERFACE - functions.php
		edit_project($cat);

	?>

	</section>
	
		
	<footer>

	</footer>
	
</body>
</html>
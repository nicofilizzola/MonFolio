<?php

	require('header.php');

	$_GET['user_id'] = $_SESSION['my_user_id'];

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

?>


<?php

    // USERS LIST - functions.php
    users_list();

    require('footer.php');

?>
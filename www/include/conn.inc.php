<?php

	$db_servername = "localhost";
	$db_uid = "root";
	$db_pwd = "";
	$db_name = "monfolio";

	$conn = mysqli_connect($db_servername, $db_uid, $db_pwd, $db_name);
	
	if(!$conn){
		die("Erreur de connection : ".mysqli_connect_error());
	}
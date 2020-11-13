<?php

	function url(url){
		if (!isset(url)){
			header('Location: index.php?cat=1');
		}
	}

header('Location: ../index.php');

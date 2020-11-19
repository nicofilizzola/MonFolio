<?php

    $button_pressed = $_POST['signout_submit'];

    // IF SIGN OUT BUTTON WAS PRESSED
    if (isset($button_pressed)){
       
        session_start();
        session_unset();
        session_destroy();
        header('Location: ../index.php?signout=success');
        exit();
 
    // IF THIS FILE WAS ACCESSED INCORRECTLY
    } else {

        header('Location: ../index.php?');
        exit();

    }

   
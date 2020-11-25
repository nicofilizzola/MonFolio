<?php

    require('functions.php');
    startup();

    require('interface.php');

    echo '
        <!doctype html>
        <html>
        <head>
            <meta charset="utf-8">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            <link rel="stylesheet" type="text/css" href="resources/css/style.css">
            <title>MonFolio</title>
        </head>

        <body>

            <nav>
            <a href="index.php">
                <img src="" alt="logo">
            </a>
        ';

    // FOR EVERYONE
    user_searchbar();

    // FOR VISITOR - interface.php
    sign_btns();

    // FOR USER - interface.php
    signout_btn();
    bo_btn();

    echo '
            </nav>
    ';


   

    
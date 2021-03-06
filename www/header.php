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

            <!-- SWIPER -->
            <link rel="stylesheet" href="https://unpkg.com/swiper/swiper-bundle.min.css">

            <link rel="stylesheet" type="text/css" href="resources/css/style.css">
            <title>MonFolio</title>
        </head>

        <body>
            <header class="flex">
            <a href="index.php">
                <img src="resources/media/img/logo.png" alt="logo" class="header__logo">
            </a>
            <nav class="flex flex--jc-c">
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
            </header>
    ';


   

    
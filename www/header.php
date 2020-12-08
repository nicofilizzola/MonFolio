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
            <header class="flex flex-jc-sb flex-ai-c">
            <a href="index.php">
                <img src="resources/img/logo.png" alt="logo" class="header__logo">
            </a>
            <nav class="flex">
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


   

    
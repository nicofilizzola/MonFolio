<?php

    require('conn.inc.php');

    function sign_in($login, $pwd, $conn){

        // NEW STATEMENT : GET USER ID FROM USERNAME
        $sql = 'SELECT user_id FROM user WHERE user_uid = ?';
        $stmt = mysqli_stmt_init($conn);

        // IF STATEMENT DOESN'T WORK
        if (!mysqli_stmt_prepare($stmt, $sql)){

            header("Location : ../index.php?error=mysqli_stmt_3");
            exit();

        } else {

            mysqli_stmt_bind_param($stmt, "s", $login);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $user_id = mysqli_fetch_assoc($result)['user_id'];

            session_start();
            $_SESSION['my_user_id'] = $user_id;
            
            header('Location: ../index.php?signin=success');

        }

    }

    function login_check($login, $pwd, $conn){

        /* NEW STATEMENT : CHECK IF USERNAME EXISTS */
        $sql = 'SELECT user_pwd FROM user WHERE user_uid = ? OR user_email = ?';
        $stmt = mysqli_stmt_init($conn);

        // IF STATEMENT DOESN'T WORK
        if(!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../index.php?error=mysql_stmt_4');
            exit();

        // IF IT WORKS
        } else {

            mysqli_stmt_bind_param($stmt, 'ss', $login, $login);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            $found_pwd = mysqli_fetch_assoc($result)['user_pwd'];
            
/*
ERROR HERE
*/

            // IF PASSWORD NOT FOUND
            if (empty($found_pwd)){
                
                return '0';
                exit();

            // IF PASSWORDS DON'T MATCH
            } elseif(!password_verify($pwd, $found_pwd)){

                return '1';
                exit();

            }

        }

    }

    $login = $_POST['login'];
    $pwd = $_POST['pwd'];
    $login_check_code = login_check($login, $pwd, $conn);

    if(empty($login) || empty($pwd)){

        header('Location: ../index.php?error=signin_0&login='.$login);
        exit();

    // IF USER/EMAIL DOESN'T EXIST
    } elseif($login_check_code == '0'){

        header('Location: ../index.php?error=signin_1');
        exit();

    // IF PASSWORD WRONG
    } elseif ($login_check_code == '1'){

        header('Location: ../index.php?error=signin_2&login='.$login);
        exit();

    // IF NO ERROR
    } else {

        sign_in($login, $pwd, $conn);
        exit();

    }


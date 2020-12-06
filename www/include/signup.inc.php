<?php

/********************************
    SETUP
 *******************************/


    require('conn.inc.php');

    function sign_up($first, $last, $uid, $email, $pwd, $conn){

        $name = $first." ".$last;
        $pwd_secure = password_hash($pwd, PASSWORD_DEFAULT);

        // NEW STATEMENT: INSERT DATA INTO SERVER
        $sql = 'INSERT INTO user(user_names, user_uid, user_email, user_pwd) VALUES(?, ?, ?, ?)';
        $stmt = mysqli_stmt_init($conn);

        // IF STATEMENT DOESN'T WORK
        if (!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../index.php?error=mysql_stmt_2');
            exit();

        // IF STATEMENT WORKS
        } else {
            
            mysqli_stmt_bind_param($stmt, "ssss", $name, $uid, $email, $pwd_secure);
            mysqli_stmt_execute($stmt);

            // CLOSE STATEMENT AND DATABASE CONNECTION
            mysqli_stmt_close($stmt);
            mysqli_close($conn);

            // REDIRECT
            header('Location: ../index.php?signup=success');

        }
    }  
    
    function uid_repeat($uid, $conn){

        $sql = "SELECT user_uid FROM user WHERE user_uid = ?";
        $stmt = mysqli_stmt_init($conn);

        // IF STATEMENT DOESN'T WORK
        if (!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../index.php?error=mysql_stmt_0');
            exit();

        // IF STATEMENT WORKS
        } else {

            mysqli_stmt_bind_param($stmt, "s", $uid);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $result_check = mysqli_stmt_num_rows($stmt);
            if ($result_check > 0){

                return true;
                exit();

            } else {

                return false;
                exit();

            }

        }

    }

    function email_repeat($email, $conn){

        $sql = "SELECT user_email FROM user WHERE user_email = ?";
        $stmt = mysqli_stmt_init($conn);

        // IF STATEMENT DOESN'T WORK
        if (!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../index.php?error=mysql_stmt_1');
            exit();

        // IF STATEMENT WORKS
        } else {

            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);
            $result_check = mysqli_stmt_num_rows($stmt);
            if ($result_check > 0){

                return true;
                exit();

            } else {

                return false;
                exit();

            }

        }

    }


/********************************
    EXEC
 *******************************/


    if (!isset($_POST['signup_submit'])){

        header('../index.php');

    }else{

        $first = $_POST['first'];
        $last = $_POST['last'];
        $uid = $_POST['uid'];
        $email = $_POST['email'];
        $pwd = $_POST['pwd'];
        $pwd_ver = $_POST['pwd_ver'];

        /* ****************
            ERROR HANDLERS
        ***************** */

        // IF ONE OF THE CHAMPS WAS NOT FILLED
        if (empty($first) || empty($last) || empty($uid) || empty($email) || empty($pwd) || empty($pwd_ver)){

            header('Location: ../index.php?error=signup_0&first='.$first.'last='.$last.'&uid='.$uid.'&email='.$email);
            exit();

        // IF FIRST NAME INVALID
        } elseif (!preg_match("/^[a-zA-Z]*$/", $first)){

            header('Location: ../index.php?error=signup_1&last='.$last.'&uid='.$uid.'&email='.$email);
            exit();

        // IF LAST NAME INVALID
        } elseif (!preg_match("/^[a-zA-Z]*$/", $last)){

            header('Location: ../index.php?error=signup_2&first='.$first.'&uid='.$uid.'&email='.$email);
            exit();

        // IF USERNAME INVALID
        } elseif (!preg_match("/^[a-zA-Z0-9]*$/", $uid)){

            header('Location: ../index.php?error=signup_3&first='.$first.'&last='.$last.'&email='.$email);
            exit();
        
        // IF EMAIL INVALID
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

			header('Location: ../index.php?error=signup_4&first='.$first.'&last='.$last.'&uid='.$uid);
            exit();
        
        // IF PASSWORDS DON'T MATCH
        } elseif ($pwd !== $pwd_ver){

            header('Location: ../index.php?error=signup_5&first='.$first.'&last='.$last.'&uid='.$uid.'&email='.$email);
            exit();

        // IF THE UID ALREADY EXISTS
        } elseif (uid_repeat($uid, $conn)){

            header('Location: ../index.php?error=signup_6&first='.$first.'&last='.$last.'&email='.$email);
            exit();

        // IF THE EMAIL ALREADY EXISTS
        } elseif (email_repeat($email, $conn)){

            header('Location: ../index.php?error=signup_7&first='.$first.'&last='.$last.'&uid='.$uid);
            exit();

        }
        
        // IF THERE IS NO ERROR
        else {

            sign_up($first, $last, $uid, $email, $pwd, $conn);

        }
        
    }
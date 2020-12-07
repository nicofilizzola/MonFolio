<?php

    // IF FILE ACCESSED VIA FORM
    if (isset($_POST['updateprofile_submit'])){

        $user_name = $_POST['user_name'];
        $user_title = $_POST['user_title'];
        $user_txt = $_POST['user_txt'];

        if (empty($user_name)){

            header('Location: ../backoffice.php?error=updateprofile_0&user_name='.$user_name.'&user_title='.$user_title.'&user_txt='.$user_txt);
            exit();

        } elseif (!preg_match("/^[a-zA-Z ]*$/", $user_name)) {

            header('Location: ../backoffice.php?error=updateprofile_1&user_title='.$user_title.'&user_txt='.$user_txt);
            exit();

        } else {
            session_start();
            require('conn.inc.php');
            $user_id = $_SESSION['my_user_id'];

            $sql = "UPDATE user SET user_names = ?, user_title = ?, user_txt = ? WHERE user_id = ?";
            $stmt = mysqli_stmt_init($conn);

            if(!mysqli_stmt_prepare($stmt, $sql)){

                header('Location: ../backoffice.php?error=mysql_stmt_updateprofile');
                exit();

            } else {

                mysqli_stmt_bind_param($stmt, 'ssss', $user_name, $user_title, $user_txt, $user_id);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                mysqli_close($conn);

                header('Location: ../backoffice.php?updateprofile=success');
                exit();

            }
        
        }

    } else {

        header('Location: ../index.php');
        exit();

    }
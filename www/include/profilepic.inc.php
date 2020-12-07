<?php

    if(isset($_POST['pic_submit'])){

        if(!empty($_FILES['pic'])){

            require('conn.inc.php');

            $sql = "INSERT INTO media(media_path, media_type) VALUES (?, ?)";
            $stmt = mysqli_stmt_init($conn);

            if (!mysqli_stmt_prepare($stmt, $sql)){

                header('Location: ../backoffice.php?error=myql_stmt_newpic');
                exit();

            } else {

                $pic_type_db = 3;
                $pic = $_FILES['pic'];
                $pic_name = $_FILES['pic']['name'];
                $pic_tmp_name = $_FILES['pic']['tmp_name'];
                $pic_size = $_FILES['pic']['size'];
                $pic_error = $_FILES['pic']['error'];
                $pic_type = $_FILES['pic']['type'];
    
                $pic_name_separated_by_dots = explode('.', $pic_name);
                $pic_extension = strtolower(end($pic_name_separated_by_dots));
                $allowed_extensions = array('jpg', 'png', 'jpeg');
    
                if (in_array($pic_extension, $allowed_extensions)){
    
                    // IF THERE'S NO ERROR WITH THIS pic
                    if ($pic_error === 0){
    
                        // IF pic SIZE UNDER 2M BITS = 2MB
                        if ($pic_size < 2000000){
    
                            // IF CONDITIONS MET, MOVE FILE TO UPLOAD FOLDER
                            $pic_name_new = uniqid('', true).'.'.$pic_extension;
                            $pic_destination = 'resources/upload/pic/'.$pic_name_new;
                            $pic_destination_relative = '../'.$pic_destination;
                            move_uploaded_file($pic_tmp_name, $pic_destination_relative);
    
                            mysqli_stmt_bind_param($stmt, 'ss', $pic_destination, $pic_type_db);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);
    
                            // NOT OPTIMAL
                            $sql_get_pic_id = "SELECT media_id FROM media ORDER BY media_id DESC LIMIT 1";
                            $result_get_pic_id = mysqli_query($conn, $sql_get_pic_id);
                            $result_array_pic = mysqli_fetch_assoc($result_get_pic_id);
                            $pic_id = $result_array_pic['media_id'];
    
                            // CREATE PROJECT pic LINK IN DB
                            $sql_insert = "UPDATE user SET user_pic_id = '$pic_id'";
                            mysqli_query($conn, $sql_insert);
                            mysqli_close($conn);
            
                            header('Location: ../backoffice.php?newpic=success');
                            exit();
    
                        // IF pic SIZE OVER 20MB
                        }else{
    
                            header('Location: ../backoffice.php?error=newpic_0');
                            exit();
    
                        }
    
                    // IF THERE'S A FILE ERROR
                    } else {
    
                        header('Location: ../backoffice.php?error=newpic_1');
                        exit();
    
                    }
    
                // IF THE FILE'S EXTENSION ISN'T ALLOWED
                } else {
    
                    header('Location: ../backoffice.php?error=newpic_2');
                    exit();
    
                }
            
            }

        }
    
    // IF FILE ACCESSED INCORRECTLY
    } else {

        header('Location: ../backoffice.php');
        exit();
    }

           
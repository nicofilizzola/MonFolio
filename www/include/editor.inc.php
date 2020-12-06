<?php 

    require('conn.inc.php');

    // IF DELETE PROJECT
    if (isset($_GET['project-delete_submit'])){
		$project_id = $_GET['project_id'];
		$sql = "DELETE FROM project WHERE user_id = $project_id";
		mysqli_query($conn, $sql);

        header('Location: ../editor.php?project-delete=success');
        exit();


    // IF DELETE MEDIA
    } elseif (isset($_POST['delete-media_submit'])){

        $delete_media = $_POST['delete-media_submit'];

        $sql = "DELETE FROM media WHERE media_id = '$delete_media'";
        $sql2 = "DELETE FROM project_media WHERE media_id = '$delete_media'";
        mysqli_query($conn, $sql);
        mysqli_query($conn, $sql2);
        
        header('Location: ../editor.php?project_id='.$_POST['project_id'].'&delete_media=success');
        exit();
    
    // IF NEW MEDIA ADDED
    } elseif (isset($_POST['new-media_submit'])){
        $project_id = $_POST['new-media_submit'];

        $sql = "INSERT INTO media(media_path, media_type) VALUES (?, ?);";
        $stmt = mysqli_stmt_init($conn);

        if (!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../editor.php?project_id='.$project_id.'&error=mysql_stmt_0');
            exit();

        // ERROR HANDLER : ONLY EXECUTE IF MEDIA INPUT WAS USE
        } elseif(!empty($_FILES['media']['name']) && !empty($_POST['video'])){

            echo 'set file and video';
            var_dump($_FILES['media']);
            echo'<br>';
            var_dump($_POST['video']);

        } else {

            // IF MEDIA TYPE IMG
            if (!empty($_FILES['media']['name'])){

                $media_type_db = 1;
                $media = $_FILES['media'];
                $media_name = $_FILES['media']['name'];
                $media_tmp_name = $_FILES['media']['tmp_name'];
                $media_size = $_FILES['media']['size'];
                $media_error = $_FILES['media']['error'];
                $media_type = $_FILES['media']['type'];
    
                $media_name_separated_by_dots = explode('.', $media_name);
                $media_extension = strtolower(end($media_name_separated_by_dots));
                $allowed_extensions = array('jpg', 'png', 'jpeg', 'pdf');
    
                if (in_array($media_extension, $allowed_extensions)){
    
                    // IF THERE'S NO ERROR WITH THIS MEDIA
                    if ($media_error === 0){
    
                        // IF MEDIA SIZE UNDER 2M BITS = 2MB
                        if ($media_size < 2000000){
    
                            // IF CONDITIONS MET, MOVE FILE TO UPLOAD FOLDER
                            $media_name_new = uniqid('', true).'.'.$media_extension;
                            $media_destination = 'resources/upload/img/'.$media_name_new;
                            $media_destination_relative = '../'.$media_destination;
                            move_uploaded_file($media_tmp_name, $media_destination_relative);
    
                            mysqli_stmt_bind_param($stmt, 'ss', $media_destination, $media_type_db);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_close($stmt);

                            // NOT OPTIMAL
                            $sql_get_media_id = "SELECT media_id FROM media ORDER BY media_id DESC LIMIT 1";
                            $result_get_media_id = mysqli_query($conn, $sql_get_media_id);
                            $result_array_media = mysqli_fetch_assoc($result_get_media_id);
                            $media_id = $result_array_media['media_id'];
    
                            // CREATE PROJECT MEDIA LINK IN DB
                            $sql_insert = "INSERT INTO project_media(project_id, media_id) VALUES ('$project_id', '$media_id')";
                            mysqli_query($conn, $sql_insert);
                            mysqli_close($conn);
            
                            header('Location: ../editor.php?new-media=success&project_id='.$project_id);
                            exit();
    
                        // IF MEDIA SIZE OVER 20MB
                        }else{
    
                            header('Location: ../editor.php?error=new-media_3&project_id='.$project_id);
                            exit();
    
                        }
    
                    // IF THERE'S A FILE ERROR
                    } else {
    
                        header('Location: ../editor.php?error=new-media_2&project_id='.$project_id);
                        exit();
    
                    }
    
                // IF THE FILE'S EXTENSION ISN'T ALLOWED
                } else {
    
                    header('Location: ../editor.php?error=new-media_1&project_id='.$project_id);
                    exit();
    
                }
                
    
            // IF MEDIA TYPE VID
            }elseif(isset($_POST['video'])){

                // ERROR HANDLER: IF LINK IS REAL
                if (!filter_var($_POST['video'],  FILTER_VALIDATE_URL)){

                    header('Location: ../editor.php?project_id='.$project_id.'&error=new-media=4');
                    exit();

                }

                $media_type_db = 2;
                
                $video_link = $_POST['video'];
                $video_link_separated = explode('watch?v=', $video_link);
                $yt_id = end($video_link_separated);
                $yt_id_separate = explode('&', $yt_id);
                $yt_actual_id = $yt_id_separate[0];

                $yt_link_prefix = 'https://www.youtube.com/embed/';
                $media_destination = $yt_link_prefix.$yt_actual_id;

                $sql = "INSERT INTO media(media_type, media_path) VALUES (?, ?);";
                $stmt = mysqli_stmt_init($conn);

                if (!mysqli_stmt_prepare($stmt, $sql)){

                    header('Location: ../editor.php?project_id='.$_POST['edit-project_submit'].'&error=mysql_stmt_1');
                    exit();

                } else {

                    mysqli_stmt_bind_param($stmt, 'ss', $media_type_db, $media_destination);
                    mysqli_stmt_execute($stmt);
                    mysqli_stmt_close($stmt);
                    mysqli_close($stmt);

                    // NOT OPTIMAL
                    $sql_get_media_id = "SELECT media_id FROM media ORDER BY media_id DESC LIMIT 1";
                    $result_get_media_id = mysqli_query($conn, $sql_get_media_id);
                    $result_array_media = mysqli_fetch_assoc($result_get_media_id);
                    $media_id = $result_array_media['media_id'];

                    // CREATE PROJECT MEDIA LINK IN DB
                    $sql_insert = "INSERT INTO project_media(project_id, media_id) VALUES ('$project_id', '$media_id')";
                    mysqli_query($conn, $sql_insert);
                    mysqli_close($conn);

                    header('Location: ../editor.php?new-media=success&project_id='.$project_id);
                    exit();
                
                }

            // IF NO MEDIA ADDED
            } else {
    
                header('Location ../editor.php?project_id='.$project_id.'&error=new-media_0');
                exit();
    
            }

        }
        
        
        
        


    
    
    // IF MODIFY EXISTING PROJECT TEXTUAL INFO
    } elseif (isset($_POST['edit-project_submit'])) {

        // ERROR HANDLER : IF ANY EMPTY INPUT
        if (empty($_POST['name']) || empty($_POST['txt']) || empty($_POST['link']) || empty($_POST['cat']) || empty($_POST['type'])){

            header('Location: ../editor.php?project_id='.$_POST['edit-project_submit'].'&project-edit_submit=&error=edit_0');
            exit();

        } else {

            // NEW STATEMENT: UPDATE SQL ROW WITH NEW DATA
            $sql = "SQL UPDATE project, tag, project_tag SET project.project_name = ?, project.project_txt = ?, project.project_link = ?, project.category_id = ?, project.type_id = ?, project_tag.tag_id = ? WHERE project_id = ? AND project.project_id = project_tag.project_id AND project_tag.tag_id = tag.tag_id";
            $stmt = mysqli_stmt_init($conn);

            // IF STATEMENT DOESN'T WORK
            if(!mysqli_stmt_prepare($stmt, $sql)){

                header('Location: ../editor.php?project_id='.$_POST['edit-project_submit'].'&error=mysql_stmt_0');
                exit();

            } else {

                $project_name = $_POST['name'];
                $project_txt = $_POST['txt'];
                $project_link = $_POST['link'];
                $project_cat = $_POST['cat'];
                $project_type = $_POST['type'];

                // THIS IS AN ARRAY IF MULTIPLE TAGS WERE SELECTED
                // IDEA : MAKE A MAX NUMBER OF TAGS (3) AND CREATE PROJECT_TAG LINKS WHERE TAG ID IS NULL IF THE LIMIT ISN'T REACHED
                
                $project_tag = $_POST['tag'];

                /*

                MISSING NEXT STEPS

                */


               // mysqli_stmt_bind_param($stmt, )

            }

        }

        

    // CREATE NEW PROJECT
    } elseif (isset($_POST['new-project_submit'])){


        // NEW STATEMENT: INSERT NEW PROJECT'S DATA INTO DB
        $sql =  "INSERT INTO project(project_name, project_txt, project_link, category_id, type_id, user_id) VALUES (?, ?, ?, ?, ?, ?); ";
        $sql2 = "INSERT INTO media(media_type, media_path) VALUES (?, ?) ;";
        $stmt = mysqli_stmt_init($conn);
        $stmt2 = mysqli_stmt_init($conn);

        // CHECK THAT SQL STATEMENT WORKS

        if (!mysqli_stmt_prepare($stmt, $sql)){

            header('Location: ../editor.php?new-project_submit=&error=mysql_stmt_0');
            exit();

        } elseif (!mysqli_stmt_prepare($stmt2, $sql2)){

            header('Location: ../editor.php?new-project_submit=&error=mysql_stmt_1');
            exit();

        } else {

            // ERROR HANDLER : IF ANY EMPTY INPUT
            if (empty($_POST['name']) || empty($_POST['txt']) || empty($_POST['link']) || empty($_POST['cat']) || empty($_POST['type'])){

                header('Location: ../editor.php?new-project_submit=&error=new_0');
                exit();

            // ERROR HANDLER : IF MISSING MEDIA
            } elseif (empty($_FILES['media']) && empty($_POST['video'])){

                header('Location: ../editor.php?new-project_submit=&error=new_4');
                exit();

            // ERROR HANLDER : IF BOTH MEDIAS USED
            } elseif(!empty($_FILES['media']) && !empty($_POST['video'])){

                header('Location: ../editor.php?new-project_submit=&error=new_5');
                exit();


            // ERROR HANDLER : IF PROVIDED LINK IS NOT A REAL LINK
            } elseif(!filter_var($_POST['link'], FILTER_VALIDATE_URL)){

                header('Location: ../editor.php?new-project_submit=&error=new_6');
                exit();

            } else {

                session_start();
                $project_name = $_POST['name'];
                $project_txt = $_POST['txt'];
                $project_link = $_POST['link'];
                $project_cat = $_POST['cat'];
                $project_type = $_POST['type'];
                $project_user = $_SESSION['my_user_id'];

                // IF USER SELECTED TAGS
                if (isset($_POST['tag'])){

                    $project_tag = $_POST['tag'];

                    // IF THERE ARE MULTIPLE TAGS
                    if(is_array($project_tag)){

                        // IF THERE ARE TWO TAGS
                        if (count($project_tag) == 2){

                            $tag_amount = 2;
                            array_push($project_tag, 0);
                            //$project_tag[2] = 0;

                        // IF THERE ARE THREE TAGS
                        } else {

                            $tag_amount = 3;

                        }
  
                    // IF THERE'S ONLY ONE TAG
                    } else {

                        $project_tag = array($project_tag, 0, 0);

                    }

                    // VARIABLES FOR EACH TAG (NULL IF NO TAG)
                    $first_tag = $project_tag[0];
                    $second_tag = $project_tag[1];
                    $third_tag = $project_tag[2];

                } else {

                    $first_tag = 0;
                    $second_tag = 0;
                    $third_tag = 0;

                }

                $media = $_FILES['media'];
                $media_name = $_FILES['media']['name'];
                $media_tmp_name = $_FILES['media']['tmp_name'];
                $media_size = $_FILES['media']['size'];
                $media_error = $_FILES['media']['error'];
                $media_type = $_FILES['media']['type'];

                $media_name_separated_by_dots = explode('.', $media_name);
                $media_extension = strtolower(end($media_name_separated_by_dots));
                $allowed_extensions = array('jpg', 'png', 'jpeg', 'pdf');

                if (in_array($media_extension, $allowed_extensions)){

                    // IF THERE'S NO ERROR WITH THIS MEDIA
                    if ($media_error === 0){

                        // IF MEDIA SIZE UNDER 2M BITS = 2MB
                        if ($media_size < 2000000){

                            // IF CONDITIONS MET, MOVE FILE TO UPLOAD FOLDER
                            $media_name_new = uniqid('', true).'.'.$media_extension;
                            $media_destination = 'resources/upload/img/'.$media_name_new;
                            $media_destination_relative = '../'.$media_destination;
                            move_uploaded_file($media_tmp_name, $media_destination_relative);

                            mysqli_stmt_bind_param($stmt, 'ssssss', $project_name, $project_txt, $project_link, $project_cat, $project_type, $project_user);
                            mysqli_stmt_execute($stmt);
                            mysqli_stmt_bind_param($stmt2, 'ss', $media_type, $media_destination);
                            mysqli_stmt_execute($stmt2);

                            // CLOSE STATEMENTS
                            mysqli_stmt_close($stmt);
                            mysqli_stmt_close($stmt2);
            
                            // SELECT LAST PROJECT CREATED BY THE USER
                            $sql_get_new_project_id = "SELECT project_id FROM project WHERE user_id = '$project_user' ORDER BY project_id DESC LIMIT 1";
                            $result_get_new_project_id = mysqli_query($conn, $sql_get_new_project_id);
                            $result_array = mysqli_fetch_assoc($result_get_new_project_id);
                            $project_id = $result_array['project_id'];
            
                            // SELECTS LAST CREATED MEDIA (NOT OPTIMAL IF WEBSITE TRAFFIC IS HIGH) - SHOULD CREATE USER ID ATTRIBUTE FOR MEDIA
                            $sql_get_media_id = "SELECT media_id FROM media ORDER BY media_id DESC LIMIT 1";
                            $result_get_media_id = mysqli_query($conn, $sql_get_media_id);
                            $result_array_media = mysqli_fetch_assoc($result_get_media_id);
                            $media_id = $result_array_media['media_id'];

                            var_dump($result_array_media);
                            echo"<br>";
                            var_dump($media_id);

                            $sql_other = "INSERT INTO project_tag(project_id, tag_id) VALUES ('$project_id', '$first_tag')";
                            $sql_other1 = "INSERT INTO project_tag(project_id, tag_id) VALUES ('$project_id', '$second_tag')";
                            $sql_other2 = "INSERT INTO project_tag(project_id, tag_id) VALUES ('$project_id', '$third_tag')";
                            $sql_other3 = "INSERT INTO project_media(project_id, media_id) VALUES ('$project_id', '$media_id')";
                            mysqli_query($conn, $sql_other);
                            mysqli_query($conn, $sql_other1);
                            mysqli_query($conn, $sql_other2);
                            mysqli_query($conn, $sql_other3);

                            mysqli_close($conn);
            
                            header('Location: ../backoffice.php?new-project=success');
                            exit();

                        // IF MEDIA SIZE OVER 20MB
                        }else{

                            header('Location: ../editor.php?new-project_submit=&error=new_3');
                            exit();

                        }

                    // IF THERE'S A FILE ERROR
                    } else {

                        header('Location: ../editor.php?new-project_submit=&error=new_2');
                        exit();

                    }

                // IF THE FILE'S EXTENSION ISN'T ALLOWED
                } else {

                    header('Location: ../editor.php?new-project_submit=&error=new_1');
                    exit();

                }

            }

        }

    }


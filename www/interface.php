<?php

    // FOR EVERYONE

    function filter_searchbar(){

        $cat = array(
			'',
			'Graphisme',
			'Audiovisuel',
			'Web Design',
			'Développement'
		);

        echo'
            <article>
                <div>
                    <h2>Projets à découvrir</h2>
                    <form action="index.php" method="get">
                        <select name="cat">
                            <option value="">Catégories</option>
                            <option value="">'.$cat[1].'</option>
                            <option value="2">'.$cat[2].'</option>
                            <option value="3">'.$cat[3].'</option>
                            <option value="4">'.$cat[4].'</option>
                        </select>
                        <select name="type">
                            <option value="">Type de projet</option>
                            <option value="1">Individuel</option>
                            <option value="2">Collectif</option>
                        </select>
                        <select name="tag">
                            <option value="">Tags</option>';

                          
        require_once('include/conn.inc.php');
        $sql = 'SELECT * FROM tag';
        $result = mysqli_query($conn, $sql);
        while ($row = mysqli_fetch_assoc($result)){
            $this_tag_id = $row['tag_id'];
            $this_tag_name = $row['tag_name'];

            echo '
                <option value="'.$this_tag_id.'">'.$this_tag_name.'</option>
            ';
            var_dump($result);
        }
        mysqli_close($conn);
        
                            
        echo'
                        </select>
                        <button type="submit">Rechercher</button>
                    </form>	
                </div>
            </article>
        ';
    }

    function user_searchbar(){
        echo '
            <form action="searchuser.php" method="get">
                <input type="text" name="user" placeholder="Rechercher un utilisateur">
                <button name="user-search_submit">Rechercher</button>
            </form>
        ';
    }



    // ONLY FOR CONNECTED USERS
    if(isset($_SESSION['my_user_id'])){

        function signout_btn(){
            echo '
                <form action="include/signout.inc.php" method="POST">
                    <button type="submit" name="signout_submit">Se déconnecter</button>
                </form>
            ';
        }

        function sign_btns(){
            return;
        }

        function signin_form(){
            return;
        }

        function signup_form(){
            return;
        }

        function cta_band(){
            return;
        }

        function bo_btn(){
            echo '
                <form action="backoffice.php">
                    <button type="submit" name="backoffice_submit" method="POST">Mes projets</button>
                </form>
            ';
        }


        // BACK OFFICE ONLY

        function np_btn(){
            echo '
                <div>
                    <form action="editor.php" method="get">

                        <button type="submit" name="new-project_submit">Nouveau projet</button>	
                    
                    </form>
                </div>
            ';
        }



    // ONLY FOR VISITORS (NOT CONNECTED)
    } else {

        function signout_btn(){
            return;
        }

        function sign_btns(){
            echo'
                <button class="btn btn--white">Se connecter</button>
                <button class="btn btn--accent">Rejoindre</button>  
            ';
        }

        function signin_form(){
            echo '
                <article>
                    <form action="include/signin.inc.php" method="POST">';

            if (isset($_GET['email'])){

                $email_cache = $_GET['email'];
                echo '<input type="text" name="login" placeholder="Adresse email/Nom d\'utilisateur" value="'.$email_cache.'" required>';

            }else{

                echo '<input type="text" name="login" placeholder="Adresse email/Nom d\'utilisateur" required>';

            }


            echo '       
                        <input type="password" name="pwd" placeholder="Mot de passe" required>
                        <!-- 
                        <input type="checkbox" name="remember" value="Rester connecté">	
                        -->
                        <button type="submit" name="signin_submit">Connexion</button>
                    </form>
                </article>
            ';
        }

        function signup_form(){

            // OPEN
            echo '
                <article>
                    <form action="include/signup.inc.php" method="POST">';

            // FIRST NAME
            if (isset($_GET['first'])){

                $first_cache = $_GET['first'];
                echo '<input type="text" name="first" placeholder="Prénom" value="'.$first_cache.'" required>';

            } else {

                echo '<input type="text" name="first" placeholder="Prénom" required>';

            }

            // LAST NAME
            if (isset($_GET['last'])){

                $last_cache = $_GET['last'];
                echo '<input type="text" name="last" placeholder="Nom" value="'.$last_cache.'" required>';

            } else {

                echo '<input type="text" name="last" placeholder="Nom" required>';

            }

            // UID
            if (isset($_GET['uid'])){

                $uid_cache = $_GET['uid'];
                echo '<input type="text" name="uid" placeholder="Nom d\'utilisateur" value="'.$uid_cache.'" required>';

            } else {

                echo '<input type="text" name="uid" placeholder="Nom d\'utilisateur" required>';

            }
                       
            // EMAIL    
            if (isset($_GET['email'])){

                $email_cache = $_GET['email'];
                echo '<input type="email" name="email" placeholder="Adresse email" value="'.$email_cache.'" required>';

            } else {

                echo '<input type="email" name="email" placeholder="Adresse email" required>';

            }

            // CLOSE (PWD, PWD VER AND BTN)
            echo '
                        <input type="password" name="pwd" placeholder="Mot de passe" required>
                        <input type="password" name="pwd_ver" placeholder="Vérifiez votre mot de passe" required>
                        <button type="submit" name="signup_submit">Commencer</button>
                    </form>
                </article>
            ';
                                    
        }

        function cta_band(){
            
            echo'
                <article>
                    <h1>Partagez vos projets créatifs</h1>

                    <form action="">
                        <button>Commencer</button>
                    </form>
                </article>
            ';

        }

        function bo_btn(){
            return;
        }

    }

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
        
        $type = array(
            'Solo',
            'En équipe'
        );

        $tag = array(
            ''
        );

        require('include/conn.inc.php');
        $sql = "SELECT tag_name FROM tag";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            array_push($tag, $row['tag_name']);
        }
        mysqli_close($conn);

        echo'
            <div class="homeBar flex">
                <h3>Projets à découvrir</h3>
                <form action="index.php" method="get" class="flex flex--center">
                    <select name="cat">';

        if (isset($_GET['cat'])){

            for ($i = 0; $i < count($cat); $i++){

                if ($i == $_GET['cat'] || $i == 0){

                    if ($i == 0){

                        echo '<option value="">Catégorie</option>';

                    } else {

                        echo '<option value="'.$i.'" selected="selected">'.$cat[$i].'</option>';

                    }                    

                } else {

                    echo '<option value="'.$i.'">'.$cat[$i].'</option>';

                }

            }

        } else {
            echo'
                <option value="">Catégorie</option>
                <option value="1">'.$cat[1].'</option>
                <option value="2">'.$cat[2].'</option>
                <option value="3">'.$cat[3].'</option>
                <option value="4">'.$cat[4].'</option>
            ';
        }

        
        echo'
            </select>
            <select name="type">
            ';

        if(isset($_GET['type'])){

            for ($i = 0; $i < count($type); $i++){

                if ($i == $_GET['type'] || $i == 0){

                    if ($i == 0){

                        echo '<option value="">Type</option>';

                    } else {

                        echo '<option value="'.$i.'" selected="selected">'.$type[$i].'</option>';

                    }                    

                } else {

                    echo '<option value="'.$i.'">'.$type[$i].'</option>';

                }
            }
        }else{
            echo'
                <option value="">Type de projet</option>
                <option value="1">Solo</option>
                <option value="2">Collectif</option>
                ';
        }
            
        echo'
            </select>
            <select name="tag">
                <option value="">Tags</option>
        ';

        if(isset($_GET['tag'])){
            for ($i = 0; $i < count($tag); $i++){
                if ($i == $_GET['tag'] || $i == 0){
                    if ($i != 0) {
                        echo '<option value="'.$i.'" selected="selected">'.$tag[$i].'</option>';
                    }                    
                } else {
                    echo '<option value="'.$i.'">'.$tag[$i].'</option>';
                }
            }
        } else {
            for($i = 1; $i < count($tag); $i++){
                echo'
                <option value="'.$i.'">'.$tag[$i].'</option>
                ';
            }
        } 

        echo'
                    </select>
                    <button type="submit" class="btn btn--secondary flex ">Rechercher<img src="resources/media/img/ico/icon-search-white.svg">
                    </button>
                </form>
            </div>
        ';
    }

    function user_searchbar(){
        echo '
            <form action="searchuser.php" method="get" class="flex flex--center">
                <button name="user-search_submit"></button>
                <input type="text" name="user" placeholder="Rechercher un utilisateur" class="input--search">
                
            </form>
        ';
    }



    // ONLY FOR CONNECTED USERS
    if(isset($_SESSION['my_user_id'])){

        function signout_btn(){
            echo '
                <form action="include/signout.inc.php" method="POST">
                    <button type="submit" name="signout_submit" class="btn btn--secondary flex">Déconnexion<img src="resources/media/img/ico/icon-logout.svg">
                    </button>
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
            require('include/conn.inc.php');
            $user_id = $_SESSION['my_user_id'];
            $sql = "SELECT media_path FROM media, user WHERE user.user_id = '$user_id' AND user.user_pic_id = media.media_id";
            $result = mysqli_query($conn, $sql);
            if (mysqli_num_rows($result) > 0){
                $media = mysqli_fetch_assoc($result)['media_path'];
            }else{
                $media = 'resources/media/img/default.jpg';
            }
            echo '
                <form action="backoffice.php" class="header__profilepic__container">
                    <button class="btn--none header__profilepic__container" type="submit" name="backoffice_submit" method="POST">
                        <img src="'.$media.'" class="header__profilepic">
                    </button>
                </form>
            ';
        }


        // BACK OFFICE ONLY

        function np_btn(){
            echo '
                <div>
                    <form action="editor.php" method="get">
                        <button id="btnPlus" class="btn btn--accent btn--none" type="submit" name="new-project_submit">
                            <img src="resources/media/ico/plus-solid.svg">
                        </button>	
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
                <button class="btn btn--primary" onclick="toggleSignIn()">Se connecter</button>
                <button class="btn btn--accent" onclick="toggleSignUp()">Rejoindre</button>  
            ';
        }

        function signin_form(){
            echo '
                <section class="sign-form__container__wrapper sign-form__container__wrapper--hidden flex flex--col flex--center" id="signInWrap">
                    <div class="sign-form__container">
                        <button class="close_button "id="closeSignIn" onclick="toggleSignIn()">
                            <img src="resources/media/img/times-solid.svg">
                        </button>
                        <h3>Se connecter à Monfolio</h2>
                        <form class="sign-form flex flex--col" action="include/signin.inc.php" method="POST">';

            if (isset($_GET['email'])){

                $email_cache = $_GET['email'];
                echo '
                <label for="login">Adresse email/Nom d\'utilisateur</label>
                <input type="text" name="login" value="'.$email_cache.'" required>';

            }else{
                
                echo '
                <label for="login">Adresse email/Nom d\'utilisateur</label>
                <input type="text" name="login" required>';

            }


            echo '
                            <label for="password">Mot de passe</label>
                            <input type="password" name="pwd"required>
                            <!-- 
                            <input type="checkbox" name="remember" value="Rester connecté">	
                            -->
                            <button type="submit" name="signin_submit" class="btn btn--accent">Connexion</button>
                        </form>
                        </div>
                </section>
            ';
        }

        function signup_form(){

            // OPEN
            echo '
                <section class="sign-form__container__wrapper sign-form__container__wrapper--hidden flex flex--col flex--center" id="signUpWrap">
                    <div class="sign-form__container">
                        <button class="close_button "id="closeSignUp" onclick="toggleSignUp()">
                            <img src="resources/media/img/times-solid.svg">
                        </button>
                        <h2>S\'inscrire sur Monfolio</h2>
                        <form class="sign-form flex flex--col" action="include/signup.inc.php" method="POST">';

            // FIRST NAME
            if (isset($_GET['first'])){

                $first_cache = $_GET['first'];
                echo '<input type="text" name="first" placeholder="Prénom" value="'.$first_cache.'" required>';

            } else {

                echo '<label for="first">Prénom</label>
                <input type="text" name="first" required>';

            }

            // LAST NAME
            if (isset($_GET['last'])){

                $last_cache = $_GET['last'];
                echo '<label for="last">Nom</label>
                <input type="text" name="last" value="'.$last_cache.'" required>';

            } else {

                echo '<label for="last">Nom</label>
                <input type="text" name="last" required>';

            }

            // UID
            if (isset($_GET['uid'])){

                $uid_cache = $_GET['uid'];
                echo '<label for="uid">Nom d\'utilisateur</label>
                <input type="text" name="uid" value="'.$uid_cache.'" required>';

            } else {

                echo '<label for="uid">Nom d\'utilisateur</label>
                <input type="text" name="uid" required>';

            }
                       
            // EMAIL    
            if (isset($_GET['email'])){

                $email_cache = $_GET['email'];
                echo '<label for="email">Adresse email</label>
                <input type="email" name="email" value="'.$email_cache.'" required>';

            } else {

                echo '<label for="email">Adresse email</label>
                <input type="email" name="email" required>';

            }

            // CLOSE (PWD, PWD VER AND BTN)
            echo '
                            <label for="password">Mot de passe</label>
                            <input type="password" name="pwd" required>
                            <label for="password">Vérifiez votre mot de passe</label>
                            <input type="password" name="pwd_ver" required>
                            <button type="submit" name="signup_submit" class="btn btn--accent">Commencer</button>
                        </form>
                    </div>
                </section>

                <script src="resources/js/closeBtns.js"></script>
            ';
                                    
        }

        function cta_band(){

            echo'
                <div class="sectionCTA flex flex--col flex--center">
                    <h1>Partagez vos projets créatifs</h1>
                    <button onclick="toggleSignUp()" class="btn btn--accent">Commencer</button>
                </div>
            ';

        }

        function bo_btn(){
            return;
        }

    }

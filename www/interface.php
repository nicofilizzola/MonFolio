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
            <article>
                <div>
                    <h2>Projets à découvrir</h2>
                    <form action="index.php" method="get">
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
                    if ($i == 0){
                        echo '<option value="">Tag</option>';
                    } else {
                        echo '<option value="'.$i.'" selected="selected">'.$tag[$i].'</option>';
                    }                    
                } else {
                    echo '<option value="'.$i.'">'.$tag[$i].'</option>';
                }
            }
        } else {
            for($i = 0; $i < count($tag); $i++){
                echo'
                <option value="'.$i.'">'.$tag[$i].'</option>
                ';
            }
        } 

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
                <button class="btn btn--white" onclick="toggleSignIn()">Se connecter</button>
                <button class="btn btn--accent" onclick="toggleSignUp()">Rejoindre</button>  
            ';
        }

        function signin_form(){
            echo '
                <section class="sign-form__container__wrapper sign-form__container__wrapper--hidden flex flex--col flex--center" id="signInWrap">
                    <div class="sign-form__container">
                        <button class="close_button "id="closeSignIn" onclick="toggleSignIn()">
                            <img src="resources/img/times-solid.svg">
                        </button>
                        <h2>Connecte-toi</h2>
                        <form class="sign-form flex flex--col" action="include/signin.inc.php" method="POST">';

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
                            <img src="resources/img/times-solid.svg">
                        </button>
                        <h2>Inscris-toi</h2>
                        <form class="sign-form flex flex--col" action="include/signup.inc.php" method="POST">';

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
                    </div>
                </section>

                <script src="resources/js/closeBtns.js"></script>
            ';
                                    
        }

        function cta_band(){
            
            echo'
                <article>
                    <h1>Partagez vos projets créatifs</h1>
                    <button onclick="toggleSignUp()">Commencer</button>
                </article>
            ';

        }

        function bo_btn(){
            return;
        }

    }

<?php

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

    } else {

        function signout_btn(){
            return;
        }

        function sign_btns(){
            echo'
                <button>Se connecter</button>
                <button>Rejoindre</button>  
            ';
        }

        function signin_form(){
            echo '
                <article>
                    <form action="include/signin.inc.php" method="POST">
                        <input type="text" name="login" placeholder="Adresse email/Nom d\'utilisateur" required>
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

    }

<?php
	require('header.php');
?>

	<nav>

		<a href="">
			<img src="" alt="logo">
		</a>
	
		<form action="" method="get">
			<input type="text" name="" id="" placeholder="Rechercher un utilisateur">
			<button>Rechercher</button>
		</form>

		<form action="">
			<button>Se connecter</button>
			<button>Rejoindre</button>
		</form>
		
	</nav>

	<section>

		<article>
			<h1>Partagez vos projets créatifs</h1>

			<form action="">
				<button>Commencer</button>
			</form>
		</article>




		<article>
			
			<form action="include/signup.inc.php" method="POST">
			
				<input type="text" name="first" placeholder="Prénom" required>
				<input type="text" name="last" placeholder="Nom" required>
				<input type="text" name="uid" placeholder="Nom d'utilisateur" required>
				<input type="email" name="email" placeholder="Adresse email" required>
				<input type="password" name="pwd" placeholder="Mot de passe" required>
				<input type="password" name="pwd_ver" placeholder="Vérifiez votre mot de passe" required>
				<button type="submit" name="signup_submit">Commencer</button>

			</form>

		</article>





		<article>

			<div>
				<h2>Projets à découvrir</h2>
				<form action="" method="get">
					<select name="" id="">
						<option value="">Catégories</option>
						<option value="1">Graphisme</option>
						<option value="2">Audiovisuel</option>
						<option value="3">Web Design</option>
						<option value="4">Développement</option>
					</select>
					<select name="" id="">
						<option value="">Type de projet</option>
						<option value="1">Individuel</option>
						<option value="2">Collectif</option>
					</select>
					<select name="" id="">
						<option value="">Tags</option>
						<option value="1">Tag 1</option>
						<option value="2">Tag 2</option>
						<option value="2">Tag 3</option>
						<option value="2">Tag 4</option>
					</select>
					<button type="submit">Rechercher</button>
				</form>	
			</div>

			<div>
			
				<div>
					<img src="" alt="">
					<p>Titre projet</p>
				</div>
			
			</div>
		
		</article>


		


	<section>
		
	<?php

		/*
		// IF USER NOT CONNECTED
		if (!isset($_SESSION['user_id'])){
			sign_in();

		// IF USER CONNECTED
		}else{
			home($_GET['user_id'], 0, 0);

		}
		*/
		
		home($_GET['user_id'], 0, 0);

	?>

	</section>
	
	<footer>

	</footer>
	
</body>
</html>
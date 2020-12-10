<?php

	$cat = array(
		'',
		'Graphisme',
		'Audiovisuel',
		'Web Design',
		'Développement'
	);

	// START SESSION AND GET CATEGORY
	function startup(){
		session_start();

		if(isset($_GET['cat'])){
			$_SESSION['cat'] = $_GET['cat'];
		}else{
			$_SESSION['cat'] = "";
		}
	}

	// INSERT CORRECT ARGUMENTS IN home FUNCTION DEPENDING ON PROVIDED FILTER SEARCHBAR INPUT

	function home(){

		if(!isset($_GET['user_id'])){
			$_GET['user_id'] = '';
		}

		if(!isset($_GET['cat'])){
			$_GET['cat'] = '';
		}

		if(!isset($_GET['type'])){
			$_GET['type'] = '';
		}

		if(!isset($_GET['tag'])){
			$_GET['tag'] = '';
		}

		$search_input = array(
			'creator_id'=>$_GET['user_id'],
			'selected_category'=>$_GET['cat'],
			'selected_type'=>$_GET['type'],
			'selected_tag'=>$_GET['tag'],
		);

		display($search_input['creator_id'], $search_input['selected_category'], $search_input['selected_type'], $search_input['selected_tag']);

	}

	/* ********************************
	FUNCTION display : DISPLAYS ALL PROJECTS
	********************************* */

	function display($creator_id, $category_id, $type_id, $tag_id){
		require("include/conn.inc.php");


		/* ********************************
			OPTION 1 : DISPLAY NEWEST
		 ********************************* */

		function display_newest($conn){
			$sql = "SELECT * FROM project ORDER BY project_id DESC";
			
			get_project_data_execute($sql, $conn, 0);
		}

		/* ********************************
			OPTION 2 : DISPLAY PROJECT BY CREATOR
		 ********************************* */
		
		function display_by_creator($creator_id, $conn){
			if (strpos($_SERVER['REQUEST_URI'], 'backoffice.php')){
				$creator_id = $_SESSION['my_user_id'];
			}
			$sql = "SELECT * FROM project WHERE project.user_id = '$creator_id' ORDER BY project_id DESC";
			

			if (strpos($_SERVER['REQUEST_URI'], 'backoffice.php')){}
			get_project_data_execute($sql, $conn, 1);
			
		}
	

		/* ********************************
			SEARCH PROJECT IMGS AND DISPLAY
		 ********************************* */

		function get_project_data_execute($sql, $conn, $error_code){

			$result = mysqli_query($conn, $sql);
			$result_check = mysqli_num_rows($result);

			if($result_check > 0){
				while ($row = mysqli_fetch_assoc($result)){
					$project_id = $row['project_id'];
					$project_name = $row['project_name'];
					$project_cat = $row['category_id'];
					$project_type = $row['type_id'];
					$creator_id = $row['user_id'];

					$this_project = $row['project_id'];

					$sql_tag = "SELECT * FROM tag, project_tag WHERE project_tag.project_id = '$this_project' AND tag.tag_id = project_tag.tag_id";
					$result_tag = mysqli_query($conn, $sql_tag);
					$tags_array = mysqli_fetch_all($result_tag);
					
					$sql_img = "SELECT media.media_path FROM media, project_media, project WHERE project.project_id = project_media.project_id AND project_media.media_id = media.media_id AND project.project_id = '$this_project' AND media.media_type = 1 LIMIT 1";
					$result_img = mysqli_query($conn, $sql_img);
					$media_cover = mysqli_fetch_assoc($result_img)['media_path'];

					display_project($this_project, $project_name, $media_cover, $project_cat, $project_type, $tags_array, $creator_id, $conn);
				}
			} else {
				
				error_empty($error_code);
			}
		}


		/* ********************************
			IF SEARCH HAS NO RESULTS
		 ********************************* */

		function error_empty($error_code){
			if($error_code == 0){
				echo '
					<div>
						<h2>Déso, mais il n\'y à rien à montrer :(</h2>
					</div>
				';
			}elseif($error_code == 1){
				if (strpos($_SERVER['REQUEST_URI'], 'backoffice.php')){
					echo '
						<div>
							<h2>Tu n\'as aucun projet à montrer, <a href="editor.php">crées en un</a></h2>
						</div>
				';
				}else{
					echo '
						<div>
							<h2>Déso, mais cet utilisateur n\'a pas encore des projets à montrer :(</h2>
						</div>
				';
				}				
			}	
		}

		// DISPLAY USER'S PROJECT
		function display_default($project_name, $media_first, $project_id, $project_cat){
			$cat = array(
				'',
				'Graphisme',
				'Audiovisuel',
				'Web Design',
				'Développement'
			);
		
			echo '
				<div class="project-grid__element">
					
					<button class="imgBtn">
						<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
					</button>
					<form method="get" action="project.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button class="titleBtn" type="submit" name="project_go">'.$project_name.'</button> | '.$cat[$project_cat].'
					</form>
				</div>';
			}

		// DISPLAY OWN PROJECT
		function display_backoffice($project_name, $media_first, $project_id){
			echo '
				<div class="project-grid__element">
					<form method="get" action="project.php">
						<button class="imgBtn" type="submit" name="project_id" value="'.$project_id.'">
							<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
						</button>
					</form>
					<div class="flex">
						<form method="get" action="project.php">
							<button class="titleBtn" type="submit" name="project_id" value="'.$project_id.'">'.$project_name.'</button>
						</form>
						<form method="get" action="editor.php">
							<input type="hidden" name="project_id" value="'.$project_id.'">
							<button class="editBtn" type="submit" name="project-go_submit">Éditer</button>
						</form>
						<form method="get" action="include/editor.inc.php">
							<input type="hidden" name="project_id" value="'.$project_id.'">
							<button class="deleteBtnBO delBtn" type="submit" name="project-delete_submit">Supprimer</button>
						</form>
					</div>
				</div>';

		}

		// DECIDE WHICH FROM THE LAST DISPLAY FUNCTIONS TO USE
		function decide_display($creator_id, $project_name, $media_first, $project_id, $project_cat){

			// IF USER IS CONNECTED
			if(isset($_SESSION['my_user_id'])){

				// IF THE PROJECT'S CREATOR ID IS THE SAME AS THE USER'S ID
				if($creator_id == $_SESSION['my_user_id']){

					display_backoffice($project_name, $media_first, $project_id);

				// IF IT IS NOT
				} else {

					display_default($project_name, $media_first, $project_id, $project_cat);

				}

			// IF USER IS NOT CONNECTED
			} else {

				display_default($project_name, $media_first, $project_id, $project_cat);

			}
		}

	
		/* ********************************
		 DISPLAY INDIVIDUAL PROJECT
		 ********************************* */
		
		function display_project($project_id, $project_name, $media_first, $project_cat, $project_type, $tags_array, $creator_id, $conn){

			// IF NO CATEGORY SELECTED OR IF SELECTED CATEGORY MATCHES
			if($_GET['cat'] == "" || $project_cat == $_GET['cat']){

				// IF NO TYPE SELECTED OR IF SELECTED TYPE MATCHES
				if($_GET['type'] == "" || $project_type == $_GET['type']){

					// IF NO TAG SELECTED
					if($_GET['tag'] == ""){

						// DISPLAY PROJECT BOX
						decide_display($creator_id, $project_name, $media_first, $project_id,$project_cat);

					// IF TAG SELECTED
					} elseif ($tags_array !== NULL) {
						
						// CHECK ALL CURRENT PROJECT'S TAGS
						for ($counter = 0; $counter < count($tags_array); $counter++){

							// IF TAG MATCHES
							if($tags_array[$counter][0] == $_GET['tag']){

								// DISPLAY PROJECT BOX
								decide_display($creator_id, $project_name, $media_first, $project_id, $project_cat);

							}
						}
					}

				}

				
			} 
			
		}

		/* ********************************
			EXECUTE
		 ********************************* */

		if(isset($creator_id) && $creator_id != "" || strpos($_SERVER['REQUEST_URI'], 'backoffice.php')){

			display_by_creator($creator_id, $conn);

		} else {

			display_newest($conn);

		} 

	}

	/* *************************
	BACK OFFICE
	************************* */

	function edit_project($cat){
		require("include/conn.inc.php");

		$project_data = 0;

		// IF SELECTED EDIT
		if (isset($_GET['project_id'])){

			$project_id = $_GET['project_id'];

			// GET PROJECT'S DATA
			$sql = "SELECT * FROM project, media, project_media WHERE project.project_id = '$project_id' AND project.project_id = project_media.project_id AND media.media_id = project_media.media_id";
			// TAGS
			$sql2 = "SELECT * FROM tag, project_tag WHERE project_tag.project_id = '$project_id' AND project_tag.tag_id = tag.tag_id";
			// MEDIA
			$sql3 = "SELECT * FROM media, project_media WHERE project_media.project_id = '$project_id' AND project_media.media_id = media.media_id";
			$result = mysqli_query($conn, $sql);
			$result2 = mysqli_query($conn, $sql2);
			$result3 = mysqli_query($conn, $sql3);

			$project_media = array();
			$project_tags = array();
			
			while ($row = mysqli_fetch_assoc($result)){

				$project_name = $row['project_name'];
				$project_txt = $row['project_txt'];
				$project_link = $row['project_link'];
				$project_cat = $row['category_id'];
				$project_type = $row['type_id'];

			}

			while ($row2 = mysqli_fetch_assoc($result2)){

				$this_tag = $row2['tag_name'];
				$project_tags[] = $this_tag;

			}

			while ($row3 = mysqli_fetch_assoc($result3)){

				$this_media = $row3['media_path'];
				$project_media[] = $this_media;

				$this_media_type = $row3['media_type'];
				$project_media_type[] = $this_media_type;

				$this_media_id = $row3['media_id'];
				$project_media_id[] = $this_media_id;

			}

			// INSERT DATA INTO ARRAY
			$project_data = array(
				$project_id,
				$project_name,
				$project_txt,			
				$project_link,
				$project_cat,			
				$project_type,
				$project_media,
				$project_tags,
				$project_media_type,
				$project_media_id
			);		
			
			edit_form($project_data, $cat);

		} else {

			edit_form(0, $cat);

		}
		

	}

	// EDITOR INTERFACE
	function edit_form($project_data, $cat){

		// IF NEW PROJECT
		if ($project_data == 0){

			echo '
			<form action="include/editor.inc.php" method="POST" enctype="multipart/form-data">
			
				<input name="name" type="text" placeholder="Donne un titre à ton projet" required>
				<textarea onkeyup="charCount(this.value)" id="projectDescription" maxlength="1000" name="txt" placeholder="Décris-nous ton projet. Qu\'est-ce que t\'a poussé à le faire ? Comment tu l\'as réalisé ? Ça t\'a apporté quoi ?" cols="30" rows="10" required></textarea>
				<span id="characterCount"></span>
				<input name="link" type="text" placeholder="T\'as un lien vers ce projet ? Donne le nous ici" required>

				<select name="cat">
					<option value="">Catégorie</option>
					<option value="1">'.$cat[1].'</option>
					<option value="2">'.$cat[2].'</option>
					<option value="3">'.$cat[3].'</option>
					<option value="4">'.$cat[4].'</option>
				</select>
				
				<input type="radio" name="type" value="1">
					<label for="1">Solo</label>
				<input type="radio" name="type" value="2">
					<label for="2">En équipe</label>';

				require('include/conn.inc.php');
				$sql = "SELECT * FROM tag";
				$result = mysqli_query($conn, $sql);

				while($row = mysqli_fetch_assoc($result)){

					if ($row['tag_id'] > 0){

						echo'
						<input type="checkbox" name="tag[]" value="'.$row['tag_id'].'">
							<label for="'.$row['tag_id'].'">'.$row['tag_name'].'</label>
						';

					}
					
				}

			echo '
				<input type="file" name="media">
				<input type="url" name="video" placeholder="Lien YouTube" disabled>
				<p>Vu que ton projet n\'a pas encore été créé, tu ne peux pas ajouter un lien YouTube. Ajoute plutôt une image de couverture et reviens après avoir enregistré ton nouveau projet pour ajouter le lien de la vidéo ;)</p>
				
				<button type="submit" name="new-project_submit">Enregistrer</button>

			</form>
		';

		// IF EDIT EXISTING PROJECT
		} else {

			echo '
			<form action="include/editor.inc.php" method="POST">
			
				<input value="'.$project_data[1].'" name="name" type="text" placeholder="Donne un titre à ton projet" required>
				<textarea onkeyup="charCount(this.value)" id="projectDescription" maxlength="1000" name="txt" placeholder="Décris-nous ton projet. Qu\'est-ce que t\'a poussé à le faire ? Comment tu l\'as réalisé ? Ça t\'a apporté quoi ?" cols="30" rows="10" required>'.$project_data[2].'</textarea>
				<span id="characterCount"></span>
				<input value="'.$project_data[3].'" name="link" type="text" placeholder="T\'as un lien vers ce projet ? Donne le nous ici" required>

				<select value="'.$project_data[4].'" name="cat">
					<option>Catégorie</option>
				';

			for ($i = 1; $i < 5; $i++){
				if ($i == $project_data[4]){
					echo'
						<option value="'.$project_data[4].'" selected="selected">'.$cat[$i].'</option>
						';
				}else{
					echo'
						<option value="'.$i.'">'.$cat[$i].'</option>
						';
				}
			}

			echo'</select>';

			// IF SOLO PROJECT
			if($project_data[5] == 1){

				echo'
				<input type="radio" name="type" value="1" checked="checked">
					<label for="1">Solo</label>
				<input type="radio" name="type" value="2">
					<label for="2">En équipe</label>';

			}elseif($project_data[5] == 2){

				// IF TEAM PROJECT
				echo'
				<input type="radio" name="type" value="1">
					<label for="1">Solo</label>
				<input type="radio" name="type" value="2" checked="checked">
					<label for="2">En équipe</label>';

			}

			// GET ALL TAGS
			require('include/conn.inc.php');
			$sql = "SELECT * FROM tag";
			$result = mysqli_query($conn, $sql);
			$this_tags = $project_data[7];
			$this_media = $project_data[6];
			$this_media_type = $project_data[8];
			$this_media_id = $project_data[9];
			
			// CHECK IF TAGS MATCH
			while ($row = mysqli_fetch_assoc($result)){

				if(in_array($row['tag_name'], $this_tags)){

					echo'
					<input type="checkbox" name="tag[]" value="'.$row['tag_id'].'" checked="checked">
						<label for="'.$row['tag_id'].'">'.$row['tag_name'].'</label>
					';
				
				} else {

					echo'
					<input type="checkbox" name="tag[]" value="'.$row['tag_id'].'">
						<label for="'.$row['tag_id'].'">'.$row['tag_name'].'</label>
					';

				}

			}

			echo '
				
				<button type="submit" name="edit-project_submit" value="'.$project_data[0].'">Enregistrer</button>

			</form>
			
			<form action="include/editor.inc.php" method="POST" enctype="multipart/form-data">

				<div class="swiper-container">
					<!-- Additional required wrapper -->
					<div class="swiper-wrapper">
						<!-- Slides -->';

					
			// IMG SWIPER

			for ($i = 0; $i < count($this_media); $i++){
			//foreach ($this_media as $media){

				echo'
					<div class="swiper-slide">';

				if ($this_media_type[$i] == 1){

					echo'
						<img src="'.$this_media[$i].'">';

					if(count($this_media) > 1){

						echo'
							<input type="hidden" name="project_id" value="'.$project_data[0].'">
							<button type="submit" class="deleteBtns" name="delete-media_submit" value="'.$this_media_id[$i].'">Supprimer cette image</button>
					';
	
					}
					
				} elseif ($this_media_type[$i] == 2){

					echo'
						<iframe width="560" height="315" src="'.$this_media[$i].'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>';

					if(count($this_media) > 1){

						echo'
							<input type="hidden" name="project_id" value="'.$project_data[0].'">
							<button type="submit" class="deleteBtns" name="delete-media_submit" value="'.$this_media_id[$i].'">Supprimer cette vidéo</button>
					';
	
					}

				}

				

				echo '</div>';

			}
						
							
			echo '		
						</div>

						<!-- If we need pagination -->
						<div class="swiper-pagination"></div>
					
					</div>

			

					<script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
					<script> 
						var mySwiper = new Swiper(".swiper-container", {
					
							// If we need pagination
							pagination: {
							el: ".swiper-pagination",
							}

						});

						var deleteBtns = document.querySelectorAll(".deleteBtns");
						var i = 0;

						deleteBtns.forEach(element => {

							element.addEventListener("click", function(event){

								if(!window.confirm("Tu es sûr de toi ?")){
									event.preventDefault();
								};

							});
						});
					</script>

					<input type="file" name="media">
					<input type="url" name="video" placeholder="Lien YouTube">

					<button type="submit" name="new-media_submit" value="'.$project_data[0].'">Enregistrer</button>

				</form>

			';

		}	

		// CHECKBOX LIMIT AND CHARACTER COUNTER
		echo '
		<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
		<script>
			$("input:checkbox").click(function() {
			var bol = $("input:checkbox:checked").length >= 3;     
			$("input:checkbox").not(":checked").attr("disabled",bol);
			});
		</script>

		<script>
			function charCount(str) {
				var lng = str.length;
				document.getElementById("characterCount").innerHTML = lng + "/1000";
			}
		</script>
		';

	}


	/***********************
	DISPLAY A SINGLE PROJECT - project.php
	**********************/

	function show_project($project_id, $cat){

		require('include/conn.inc.php');

		// IF NO PROJECT ID
		if(empty($_GET['project_id'])){

			header('Location: index.php');

		// IF PROJECT ID SET
		} else {

			$project_id = $_GET['project_id'];
			$sql = "SELECT * FROM project where project_id = '$project_id'";
			$result = mysqli_query($conn, $sql);
			$project_data = mysqli_fetch_assoc($result);
			$cat_index = $project_data['category_id'];

			$sql2 = "SELECT * FROM media, project_media WHERE project_media.project_id = '$project_id' AND media.media_id = project_media.media_id";
			$result2 = mysqli_query($conn, $sql2);

			$sql3 = "SELECT * FROM tag, project_tag WHERE project_tag.project_id = '$project_id' AND tag.tag_id = project_tag.tag_id";
			$result3 = mysqli_query($conn, $sql3);

			echo '
			<article class="flex project__article">
				<div class="swiper-container">
					<!-- Additional required wrapper -->
					<div class="swiper-wrapper">';

			while($row2 = mysqli_fetch_assoc($result2)){
				$media_type = $row2['media_type'];
				$media_id = $row2['media_id'];
				$media_path = $row2['media_path'];

				// IF THIS MEDIA IS IMG
				if ($media_type == 1){

					echo '
						<div class="swiper-slide">
							<img src="'.$media_path.'">
						</div>
					';

				// IF MEDIA TYPE IS VIDEO
				} elseif($media_type == 2){

					echo '
						<div class="swiper-slide">
								<iframe width="560" height="315" src="'.$media_path.'" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
						</div>
					';
				}

			}

			echo'
					</div>
					<!-- If we need pagination -->
					<div class="swiper-pagination"></div>

				</div>

			</article>';

			echo '
			<article class="flex flex--col project__article">

				<h3>'.$project_data['project_name'].'</h3>

				<p>'.$project_data['project_txt'].'</p>

				<p class="span"><a href="'.$project_data['project_link'].'" target="_blank">Clique ici pour en savoir plus</a></p>

				<p class="span"><span class="cat cat'.$cat_index.'">'.$cat[$cat_index].'</span></p>';

			// IF SOLO PROJECT
			if ($project_data['type_id'] == 1){

				echo '<p class="span"><span class="type">Projet solo</span></p>';

			// IF TEAM PROJECT
			} elseif ($project_data['type_id'] == 2){

				echo '<p class="span"><span class="type">Projet en équipe</span></p>';

			}

			echo '<p class="span">';

			while ($row3 = mysqli_fetch_assoc($result3)){

				if ($row3['tag_id'] != 0){

					echo '<span class="tag">'.$row3['tag_name'].'</span> ';

				}

			}

			echo '
				</p>
			</article>
			';

		}

	}


	/******************************
	 * USER SEARCHBAR
	 *****************************/

	 function users_list(){

		// IF ACCESSED searchuser.php DIRECTLY BY URL -> REDIRECT
		if (!isset($_GET['user-search_submit'])){

			header('Location: index.php');
			exit();

		// IF DIDN'T SEARCH ANYTHING
		}elseif (empty($_GET['user'])){

			header('Location: index.php?error=searchuser_0');
			exit();

		}else{

			$input = $_GET['user'];

			// IF SPECIALS ON INPUT
			if (!preg_match("/^[a-zA-Z0-9]*$/", $input)){

				header('Location: index.php?error=searchuser_1');
				exit();

			// IF EVERYTHING OK
			}else{

				require('include/conn.inc.php');

				$sql = "SELECT user_id, user_uid, user_names, user_pic_id, user_title FROM user WHERE user_names LIKE ? OR user_uid LIKE ?";
				$stmt = mysqli_stmt_init($conn);

				// IF STATEMENT WRONG
				if(!mysqli_stmt_prepare($stmt, $sql)){

					header('Location: index.php?error=mysql_stmt_searchuser');
					exit();

				} else {

					$stmt_input = '%'.$input.'%';
					mysqli_stmt_bind_param($stmt, "ss", $stmt_input, $stmt_input);
					mysqli_stmt_execute($stmt);
					$result = mysqli_stmt_get_result($stmt);
					$result_check = mysqli_num_rows($result);
					mysqli_stmt_close($stmt);
					
					if($result_check > 0){
						echo'<div class="flex flex--col userlist">';

						while($row = mysqli_fetch_assoc($result)){
							$user_name = $row['user_names'];
							$user_id = $row['user_id'];
							$user_title = $row['user_title'];
							$user_media = $row['user_pic_id'];

							echo'
								<div class="flex userlist__element">
							';

							if ($user_media == 0){
								
								echo '
									<div>
										<img src="resources/media/img/default.jpg" class="profilepic">
									</div>';

							}else{

								$sql2 = "SELECT media.media_path FROM media, user WHERE user.user_pic_id = media.media_id AND user_id = '$user_id'";
								$result2 = mysqli_query($conn, $sql2);
								$media_path = mysqli_fetch_assoc($result2)['media_path'];
								mysqli_close($conn);

								echo '
									<div class="flex">
										<img src="'.$media_path.'" class="profilepic">
									</div>';

							}
							

							echo'
								<div class="flex flex--col">
									<p>'.$user_name.'</p>
									<p>'.$user_title.'</p>
									<form method="get" action="index.php">
										<button name="user_id" value="'.$user_id.'">Voir ses projets</button>
									</form>
								</div>
							</div>

						';

						}

						echo '</div>';

					// IF NO RESULT
					} else {

						echo '
						<div>
							<h2>Déso, mais on n\'a pas pu trouver cet utilisateur</h2>
						</div>';

					}
				}
			}
		}
	}


	function user_info(){

		// IF NOT OWN PROFILE
		if(isset($_GET['user_id'])){

			info($_GET['user_id']);

		// IF BACKOFFICE
		} elseif(isset($_SESSION['my_user_id']) && strpos($_SERVER['REQUEST_URI'], 'backoffice.php')){

			info($_SESSION['my_user_id']);

		}

	}

	function info($user_id){

		require('include/conn.inc.php');
		$sql = "SELECT * FROM user WHERE user_id = '$user_id'";
		$result = mysqli_query($conn, $sql);
		$user_data = mysqli_fetch_assoc($result);

		// IF USER HAS NO PROFILE PIC
		if($user_data['user_pic_id'] == 0){

			$media_path = 'resources/media/img/default.jpg';

		// IF USER HAS PROFILE PIC
		}else{

			$sql2 = "SELECT media.media_path FROM media, user WHERE user.user_pic_id = media.media_id AND user_id = '$user_id'";
			$result2 = mysqli_query($conn, $sql2);
			$media_path = mysqli_fetch_assoc($result2)['media_path'];
			mysqli_close($conn);

		}

		$user_names = $user_data['user_names'];
		$user_title = $user_data['user_title'];
		$user_txt = $user_data['user_txt'];
		$user_uid = $user_data['user_uid'];

		echo '
			<div class="flex userinfo__element">
				<div>
					<img src="'.$media_path.'">';

		
		if (isset($_SESSION['my_user_id']) && $_SESSION['my_user_id'] == $user_id){

			echo'
				<form action="include/profilepic.inc.php" method="post" enctype="multipart/form-data">
					<input type="file" name="pic" required>
					<button type="submit" name="pic_submit">Enregistrer nouvelle photo de profil</button>
				</form>

				</div>
				<div>
					<form action="include/updateprofile.inc.php" method="post">
						<input type="text" value="'.$user_names.'" name="user_name" placeholder="Ton nom" required>
						<input type="text" value="'.$user_title.'" name="user_title" placeholder="Tu fais quoi ? (Développeur, Graphiste, ...)">
						<textarea name="user_txt" placeholder="Dis-nous ton histoire">'.$user_txt.'</textarea>
						<button type="submit" name="updateprofile_submit">Enregistrer</button>
					</form>
				</div>
			</div>
			';
			
		} else {

			echo'
				</div>
				<div>
					<h2>'.$user_names.'</h2>
					<h4>'.$user_title.'</h4>
					<p>@'.$user_uid.'</p>
					<p>'.$user_txt.'</p>
				</div>
			</div>
		';
		}
	}
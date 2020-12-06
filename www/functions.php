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
			$sql = "SELECT * FROM project WHERE project.user_id = '$creator_id' ORDER BY project_id DESC";

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
				echo '
					<div>
						<h2>Déso, mais cet utilisateur n\'a pas encore des projets à montrer :(</h2>
					</div>
				';
			}	
		}

		// DISPLAY USER'S PROJECT
		function display_default($project_name, $media_first, $project_id){
			echo '
				<div>
					<h4>'.$project_name.'</h4>
					<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
					<form method="get" action="project.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button type="submit" name="project_go">Go !</button>
					</form>
				</div>';
			}

		// DISPLAY OWN PROJECT
		function display_backoffice($project_name, $media_first, $project_id){
			echo '
				<div>
					<h4>'.$project_name.'</h4>
					<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
					<form method="get" action="project.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button type="submit" name="project_go">Go !</button>
					</form>
					<form method="get" action="editor.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button type="submit" name="project-go_submit">Éditer</button>
					</form>
					<form method="get" action="include/editor.inc.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button class="deleteBtnBO" type="submit" name="project-delete_submit">Supprimer</button>
					</form>
				</div>';

		}

		// DECIDE WHICH FROM THE LAST DISPLAY FUNCTIONS TO USE
		function decide_display($creator_id, $project_name, $media_first, $project_id){

			// IF USER IS CONNECTED
			if(isset($_SESSION['my_user_id'])){

				// IF THE PROJECT'S CREATOR ID IS THE SAME AS THE USER'S ID
				if($creator_id == $_SESSION['my_user_id']){

					display_backoffice($project_name, $media_first, $project_id);

				// IF IT IS NOT
				} else {

					display_default($project_name, $media_first, $project_id);

				}

			// IF USER IS NOT CONNECTED
			} else {

				display_default($project_name, $media_first, $project_id);

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

						echo "Project id : ".$project_id;
						echo "<br>";
						echo "Creator id : ".$creator_id;

						// DISPLAY PROJECT BOX
						decide_display($creator_id, $project_name, $media_first, $project_id);

					// IF TAG SELECTED
					} elseif ($tags_array !== NULL) {
						
						// CHECK ALL CURRENT PROJECT'S TAGS
						for ($counter = 0; $counter < count($tags_array); $counter++){

							// IF TAG MATCHES
							if($tags_array[$counter][0] == $_GET['tag']){

								echo "Project id : ".$project_id;
								echo "<br>";
								echo "Creator id : ".$creator_id;

								// DISPLAY PROJECT BOX
								decide_display($creator_id, $project_name, $media_first, $project_id);

							}
						}
					}

				}

				
			} 
			
		}

		/* ********************************
			EXECUTE
		 ********************************* */

		if(isset($creator_id) && $creator_id != ""){

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

			echo 'new project';

			echo '
			<form action="include/editor.inc.php" method="POST" enctype="multipart/form-data">
			
				<input name="name" type="text" placeholder="Donne un titre à ton projet" required>
				<textarea name="txt" placeholder="Décris-nous ton projet. Qu\'est-ce que t\'a poussé à le faire ? Comment tu l\'as réalisé ? Ça t\'a apporté quoi ?" cols="30" rows="10" required></textarea>
				<input name="link" type="text" placeholder="T\'as un lien vers ce projet ? Donne le nous ici" required>

				<select name="cat">
					<option value="">Catégorie</option>
					<option value="1">Graphisme</option>
					<option value="2">Audiovisuel</option>
					<option value="3">Web Design</option>
					<option value="4">Développement</option>
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

			echo 'edit project';

			echo '
			<form action="include/editor.inc.php" method="POST">
			
				<input value="'.$project_data[1].'" name="name" type="text" placeholder="Donne un titre à ton projet" required>
				<textarea name="txt" placeholder="Décris-nous ton projet. Qu\'est-ce que t\'a poussé à le faire ? Comment tu l\'as réalisé ? Ça t\'a apporté quoi ?" cols="30" rows="10" required>'.$project_data[2].'</textarea>
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
						<option value="'.$i.'" selected="selected">'.$cat[$i].'</option>
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
			<article>
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
			<article>

				<h3>'.$project_data['project_name'].'</h3>

				<p>'.$project_data['project_txt'].'</p>

				<a href="'.$project_data['project_link'].'" target="_blank">Clique ici pour en savoir plus</a>

				<p><span class="cat">'.$cat[$cat_index].'</span></p>';

			// IF SOLO PROJECT
			if ($project_data['type_id'] == 1){

				echo '<p><span class="type">Projet solo</span></p>';

			// IF TEAM PROJECT
			} elseif ($project_data['type_id'] == 2){

				echo '<p><span class="type">Projet en équipe</span></p>';

			}

			echo '<p>';

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

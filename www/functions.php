<?php

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
			OPTION 1 : DISPLAY PROJECT BY CREATOR
		 ********************************* */
		
		function display_by_creator($creator_id, $conn){
			$sql = "SELECT * FROM project WHERE project.user_id = '$creator_id'";

			get_project_data_execute($sql, $conn, 1);
		}
		

		/* ********************************
			OPTION 2 : DISPLAY NEWEST
		 ********************************* */

		function display_newest($conn){
			$sql = "SELECT * FROM project ORDER BY DESC";
			
			get_project_data_execute($sql, $conn, 0);
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
					
					

					$sql_img = "SELECT media.media_path FROM media, project_media, project WHERE project.project_id = project_media.project_id AND project_media.media_id = media.media_id AND project.project_id = '$this_project'";
					$result_img = mysqli_query($conn, $sql_img);
					$media_array = mysqli_fetch_assoc($result_img);
					$media_cover = $media_array['media_path'];

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
						echo '
							<div>
								<h4>'.$project_name.'</h4>
								<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
								<form method="get" action="project.php">
									<input type="hidden" name="project_id" value="'.$project_id.'">
									<button type="submit" name="project_go">Go !</button>
								</form>';

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
								echo '
								<div>
									<h4>'.$project_name.'</h4>
									<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
									<form method="get" action="project.php">
										<input type="hidden" name="project_id" value="'.$project_id.'">
										<button type="submit" name="project_go">Go !</button>
									</form>';
							}
						}

					}

					display_tags($project_id, $conn);

					echo '		
						</div>
					';

				}

				
			} 
			
		}


		/* ********************************
		 DISPLAY TAGS
		********************************* */

		function display_tags($project_id, $conn){
			// SQL QUERY TO GET TAGS
			$sql_tags = "SELECT tag.tag_name, tag.tag_id FROM project, project_tag, tag WHERE project.project_id = project_tag.project_id AND project_tag.tag_id = tag.tag_id AND project.project_id = '$project_id'";
			$result_tags = mysqli_query($conn, $sql_tags);
			
			// GET TAGS AND STOCK THEM IN $project_tags
			$project_tags = array();
			while ($row = mysqli_fetch_assoc($result_tags)){
				$new_tag = $row['tag_name'];

				array_push($project_tags, $new_tag);
			}

			// DISPLAY TAGS INSIDE $project_tags
			for ($i = 0; $i < count($project_tags); $i++){
				echo '
					<span>'.$project_tags[$i].'</span>
				';
			}
		}


		/* ********************************
			EXECUTE
		 ********************************* */


		if(isset($creator_id)){

			display_by_creator($creator_id, $conn);

		} else {

			display_newest($conn);

		}

	}

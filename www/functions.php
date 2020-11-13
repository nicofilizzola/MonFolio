<?php

	// START SESSION AND GET CATEGORY
	function session(){
		session_start();

		if(isset($_GET['cat'])){
			$_SESSION['cat'] = $_GET['cat'];
		}else{
			$_SESSION['cat'] = "";
		}
	}

	// PROJECT DISPLAY
	function home($creator_id, $category_id, $type_id){
		require("include/conn.inc.php");

		// IF USER HAS NO PROJECTS
		function error_empty(){
			echo '
				<div>
					<h2>Déso, mais cet utilisateur n\'a pas encore des projets à montrer :(</h2>
				</div>
			';
		}



		/* ********************************
		 DISPLAY TAGS
		********************************* */

		function display_tags($project_id, $conn){
			// SQL QUERY TO GET TAGS
			$sql_tags = "SELECT * FROM project, project_tag, tag WHERE project.project_id = project_tag.project_id AND project_tag.tag_id = tag.tag_id AND project.project_id = '$project_id'";
			$result_tags = mysqli_query($conn, $sql_tags);
			
			// GET TAGS AND STOCK THEM IN $project_tags
			$project_tags = array();
			while ($row = mysqli_fetch_assoc($result_tags)){
				$new_tag = $row['tag_name'];

				/*if (!isset($project_tags)){
				$project_tags = array()
				}*/
				array_push($project_tags, $new_tag);
				var_dump($project_tags);
			}

			// DISPLAY TAGS INSIDE $project_tags
			for ($i = 0; $i < count($project_tags); $i++){
				echo '
					<span>'.$project_tags[$i].'</span>
				';
			}
		}


		/* ********************************
		 DISPLAY PROJECTS
		 ********************************* */
		
		function display_project($project_id, $project_name, $media_first, $conn){

			// DISPLAY PROJECT BOX
			echo '
				<div>
					<h4>'.$project_name.'</h4>
					<img src="'.$media_first.'" alt="Photo du projet : '.$project_name.'">
					<form method="get" action="project.php">
						<input type="hidden" name="project_id" value="'.$project_id.'">
						<button type="submit" name="project_go">Go !</button>
					</form>';

			display_tags($project_id, $conn);

			echo '		
				</div>
			';

		}


		/* ********************************
		 DISPLAY PROJECT BY CREATOR
		 ********************************* */
		
		function display_by_creator($creator_id, $conn){
			$sql = "SELECT * FROM project WHERE project.user_id = '$creator_id'";
			$result = mysqli_query($conn, $sql);
			$result_check = mysqli_num_rows($result);

			if($result_check > 0){
				while ($row = mysqli_fetch_assoc($result)){
					$project_id = $row['project_id'];
					$project_name = $row['project_name'];

					$this_project = $row['project_id'];
					$sql_img = "SELECT media.media_path FROM media, project_media, project WHERE project.project_id = project_media.project_id AND project_media.media_id = media.media_id AND project.project_id = '$this_project'";
					$result_img = mysqli_query($conn, $sql_img);
					$media_array = mysqli_fetch_assoc($result_img);
					$media_cover = $media_array['media_path'];

					echo $this_project;
					echo "<br>";
					echo $creator_id;

					display_project($project_id, $project_name, $media_cover, $conn);
				}
			}else{
				error_empty();
			}
		}
		

		/* ********************************
		
		 ********************************* */

		if($category_id == "" && $type_id == ""){
			display_by_creator($creator_id, $conn);
		} 

	}

<?php
	require('header.php');
?>

	<span class="errormsg">
		<?php
			errormsg(); // functions.php
		?>
	</span>

    <section class="flex flex--col flex--ai-c">

	<?php
		// USER'S DETAILS - functions.php
		user_info();
		// NEW PROJECT BUTTON - interface.php
		np_btn();
	?>

	<div class="project-grid">

			<?php
				// PROJECTS DISPLAY - functions.php
				home($cat);
			?>
		
		</div>

	</section>

	
	<script>
	
		var deleteBtns = document.querySelectorAll(".deleteBtnBO");
		var i = 0;

		deleteBtns.forEach(btn => {

			btn.addEventListener("click", function(event){

				if(!window.confirm("Tu es sûr de toi ?")){
					event.preventDefault();
				};

			});
		});

	</script>

	<?php

		require('footer.php');

	?>
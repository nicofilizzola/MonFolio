<?php
	require('header.php');
?>

    <section>

	<div class="project-grid">

			<?php

				// USER'S DETAILS - functions.php
				user_info();

				// NEW PROJECT BUTTON - interface.php
				np_btn();

				// PROJECTS DISPLAY - functions.php
				home();

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
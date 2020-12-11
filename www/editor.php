<?php
	require('header.php');
?>

	<span class="errormsg">
		<?php
			errormsg(); // functions.php
		?>
	</span>

    <section class="flex flex--col flex--ai-c editor">

		<?php
			// EDIT PROJECT INTERFACE - functions.php
			edit_project($cat);
		?>

	</section>
		
	
	<?php
		require('footer.php');
	?>

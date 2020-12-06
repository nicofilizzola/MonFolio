<?php

	require('header.php');


	$_GET['user_id'] = $_SESSION['my_user_id'];

	if (isset($_SESSION['my_user_id'])){
		echo 'my user id '.$_SESSION['my_user_id'];
	}

?>

    <section>

		<?php

			// FOR EVERYONE
			filter_searchbar();

		?>

	</section>

    <section>

        <?php

            show_project($_GET['project_id'], $cat);

        ?>

    </section>

    <script src="https://unpkg.com/swiper/swiper-bundle.min.js"></script>
    <script>
        var mySwiper = new Swiper('.swiper-container', {
            // If we need pagination
            pagination: {
                el: '.swiper-pagination',
            }
        })
    
    </script>

	<footer>

	</footer>
	
</body>
</html>
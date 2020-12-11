<?php
	require('header.php');
?>

    <section>

        <div class="flex project">

            <?php

                show_project($_GET['project_id'], $cat);

            ?>

        <div>

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

    <?php

        require('footer.php');

    ?>
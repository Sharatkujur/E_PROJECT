<?php
$active = "Home";
include("functions.php");
include("header.php");
?>

<style>
    /* Set the same height and width for the banner images */
    .banner-section .single-banner img {
        width: 100%;  /* Ensure it stretches the full width */
        height: 300px;  /* Adjust the height to your desired size */
        object-fit: cover;  /* Ensures the image covers the area without distortion */
    }

    /* Add horizontal spacing between banners */
    .banner-section .single-banner {
        margin-top: 30px;  /* Adjust the right margin to create spacing */
    }

    /* Remove margin on the last column */
    .banner-section .single-banner:last-child {
        margin-right: 0;
    }
    .single-hero-items {
    position: relative;
    width: 100%;
    height: 500px; /* Adjust height as needed */
    background-size: cover;
    background-position: center;
    background-repeat: no-repeat;
}
</style>

<section class="hero-section">
    <div class="hero-items owl-carousel">

        <?php

        $get_slides = "select * from slider LIMIT 0,1";
        $run_slider = mysqli_query($con, $get_slides);

        while ($row_slides = mysqli_fetch_array($run_slider)) {

            $slide_name = $row_slides['slide_name'];
            $slide_image = $row_slides['slide_image'];
            $slide_heading = $row_slides['slide_heading'];
            $slide_text = $row_slides['slide_text'];

            echo " 

            <div class='single-hero-items set-bg text-white' data-setbg='img/ppowder.jpg'>


                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-5'>
                            <h1>$slide_heading</h1>
                            <p>$slide_text
                            </p>
                            <!-- <a href='shop.php' class='primary-btn'>Shop Now</a> -->
                        </div>
                    </div>
                   <!--  <div class='off-card'>
                         <h2>Up to <span>60%</span></h2>
                  </div>  -->
                </div>
            </div>
                ";
        }

        $get_slides = "select * from slider LIMIT 1,2";
        $run_slider = mysqli_query($con, $get_slides);

        while ($row_slides = mysqli_fetch_array($run_slider)) {

            $slide_name = $row_slides['slide_name'];
            $slide_image = $row_slides['slide_image'];
            $slide_heading = $row_slides['slide_heading'];
            $slide_text = $row_slides['slide_text'];

            echo "
            <div class='single-hero-items set-bg' data-setbg='img/ghealth.jpg'>
                <div class='container'>
                    <div class='row'>
                        <div class='col-lg-5'>
                            <h1 style='color: black;'>$slide_heading</h1>
                            <p style='color: brown;'>$slide_text</p>
                            <!-- <a href='shop.php' class='primary-btn'>Shop Now</a> -->
                        </div>
                    </div>
                </div>
            </div>";
        }

        ?>

    </div>
</section>

<!-- Banner Section Begin -->

<div class="banner-section spad">
    <div class="container-fluid">
        <div class="row g-4"> <!-- Add the 'g-4' class here for consistent column gaps -->
            <div class="col-lg-4">
                <a href='shop.php?p_cat_id=1'>
                    <div class="single-banner">
                        <img src="img/run.jpeg" alt="Nutrtion">
                        <div class="inner-text">
                            <h4>Nutrition</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href='shop.php?p_cat_id=2'>
                    <div class="single-banner">
                        <img src="img/86.jpg" alt="Beauty">
                        <div class="inner-text">
                            <h4>Beauty</h4>
                        </div>
                    </div>
                </a>

            </div>
            <div class="col-lg-4">
                <a href='shop.php?p_cat_id=3'>
                    <div class="single-banner">
                        <img src="img/living.png" alt="Home and Living">
                        <div class="inner-text">
                            <h4>Home Living And Kitchen</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href='shop.php?p_cat_id=4'>
                    <div class="single-banner">
                        <img src="img/personal.jpg" alt="Personal Care">
                        <div class="inner-text">
                            <h4>Personal Care</h4>
                        </div>
                    </div>
                </a>
            </div>
            <div class="col-lg-4">
                <a href='shop.php?p_cat_id=5'>
                    <div class="single-banner">
                        <img src="img/more.jpg" alt="More Products">
                        <div class="inner-text">
                            <h4>More Products</h4>
                        </div>
                    </div>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Women Banner Section Begin -->
<!-- 
<section class="women-banner spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3">
                <div class="product-large set-bg" data-setbg="img/park.jpg">
                    <h2></h2>
                    <a href="shop.php?cat_id=2">Discover More</a>
                </div>
            </div>
            <div class="col-lg-8 offset-lg-1">
                <div class="filter-control">
                    <h3> Hot Products </h3>
                </div>
                <div class="product-slider owl-carousel">

                    <?php
                    // getWProduct();
                    ?>
                </div>
            </div>
        </div>
    </div>
</section>
 -->

<!-- Man Banner Section Begin -->
<!-- 
<section class="man-banner spad">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-8">
                <div class="filter-control">
                    <h3> Hot Products </h3>
                </div>
                <div class="product-slider owl-carousel">
                    <?php
                    // getMProduct();
                    ?>

                </div>
            </div>
            <div class="col-lg-3 offset-lg-1">
                <div class="product-large set-bg m-large" data-setbg="img/men-large.jpg">
                    <h2>Men’s</h2>
                    <a href="shop.php?cat_id=1">Discover More</a>
                </div>
            </div>
        </div>
    </div>
</section> -->

<!-- Footer -->

<?php
include('footer.php');

if (isset($_GET['stat'])) {

    echo "
        <script>
                bootbox.alert({
                    message: 'Welcome! You are logged in.',
                    backdrop: true
                });
        </script>";
}
?>

</body>

</html> 
<!-- please fix the size of banner images of slider so that full image can be shown -->
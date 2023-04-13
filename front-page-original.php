<?php
/**
 * Template Name: Homepage
 *
 * @package towerpf-site
 *
 */
?>

<?php get_header(); ?>
<div class="Home-page">

    <?php

        while ( have_posts() ) :
            the_post();
           
            get_template_part( 'template-parts/content', 'page' );
           
                       
        endwhile; // End of the loop.
    ?>
   
</div>


<div class="contain">
        <img class="top" src="<?php the_field('top'); ?>" alt="">
        <div class="center1"><?php the_field('location'); ?></div>
        <div class="center2"><?php the_field('date'); ?></div>
        <div class="center3"><?php the_field('title'); ?></div>
        <div class="center4"><?php the_field('countdown'); ?></div>
</div>

<img class="middle" src="<?php the_field('middle'); ?>" alt="">

<img class="bottom" src="<?php the_field('bottom'); ?>" alt="">

<!-- Start of first featurette -->
<div class="row featurette">
      <div class="col-md-75">
        <h2 class="featurette-heading"><?php the_field('heading_1'); ?><br> <?php the_field('heading_2'); ?></h2>
        <p class="lead"><?php the_field('paragraph'); ?></p>
        <a href="#" class="button">VIEW PORCH MAP</a>
      </div>

      <div class=" row4 col-md-5">
        <img class="img1" src="<?php the_field('image_1'); ?>" width="300" height="300" alt="">
        <img class="img2" src="<?php the_field('image_2'); ?>" width="145" height="145" alt="">
        <img class="img3" src="<?php the_field('image_3'); ?>" width="145" height="145" alt="">
    </div>
</div>
<!-- End of first featurette -->
<!-- Start of second featurette -->
<div class="row featurette color">
        <div class="col-md-77">
            <h2 class="featurette-heading2"><?php the_field('carousel_heading'); ?></h2>
            <p class="lead2"><?php the_field('carousel_paragraph'); ?></p>
            <a href="#" class="button5">VIEW ALL PORCHES</a>
        </div>

        <div class="col-md-57">
        <div id="porch_carousel" class="carousel" data-bs-ride="carousel">
    <div class="carousel-indicators" id="porch_indicator">
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="1" aria-label="Slide 2"></button>
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="2" aria-label="Slide 3"></button>
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="3" aria-label="Slide 4"></button>
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="4" aria-label="Slide 5"></button>
        <button type="button" data-bs-target="#porch_carousel" data-bs-slide-to="5" aria-label="Slide 6"></button>
    </div>
    <div class="carousel-inner" id="test_carousel">
        <!-- card 1 -->
        <div class="carousel-item active">
                <div class="card">
                    <h5 class="card-title">ROCKING PORCH</h5>
                    <p class="address">123 Olive Dr. Fresno CA 93728</p>
                    <img class="image1" src="<?php the_field('card_image_1'); ?>" class="card-img-top" alt="...">
                    <div class="card-body">
                        <p class="item">YOGA</p>
                        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
                        <p class="time"> <b> 11am - 2pm </b> </p>
                        <a href="#" class="button4">SEE LINEUP</a>
                    </div>
                </div>
            </div>
            <!-- card 2 -->
        <div class="carousel-item">
        <div class="card">
        <h5 class="card-title">GREEN HOUSE PORCH</h5>
        <p class="address">123 Olive Dr. Fresno CA 93728</p>
        <img class="image1" src="<?php the_field('card_image_2'); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <p class="item">YOGA</p>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        <p class="time"> <b> 11am - 2pm </b> </p>
        <a href="#" class="button4">SEE LINEUP</a>
    </div>
    </div>
        </div>
        <!-- card 3 -->
        <div class="carousel-item">
        <div class="card">
        <h5 class="card-title">YOGA BEARS</h5>
        <p class="address">123 Olive Dr. Fresno CA 93728</p>
        <img class="image1" src="<?php the_field('card_image_3'); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <p class="item">YOGA</p>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        <p class="time"> <b> 11am - 2pm </b> </p>
        <a href="#" class="button4">SEE LINEUP</a>
    </div>
    </div>
        </div>
        <!-- card 4 -->
        <div class="carousel-item">
        <div class="card">
        <h5 class="card-title">THE SMITHS</h5>
        <p class="address">123 Olive Dr. Fresno CA 93728</p>
        <img class="image1" src="<?php the_field('card_image_4'); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <p class="item">YOGA</p>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        <p class="time"> <b> 11am - 2pm </b> </p>
        <a href="#" class="button4">SEE LINEUP</a>
    </div>
    </div>
        </div>
        <!-- card 5 -->
        <div class="carousel-item">
        <div class="card">
        <h5 class="card-title">THE HOUSE ON OLIVE</h5>
        <p class="address">123 Olive Dr. Fresno CA 93728</p>
        <img class="image1" src="<?php the_field('card_image_5'); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <p class="item">YOGA</p>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        <p class="time"> <b> 11am - 2pm </b> </p>
        <a href="#" class="button4">SEE LINEUP</a>
    </div>
    </div>
        </div>
        <!-- card 6 -->
        <div class="carousel-item">
        <div class="card">
        <h5 class="card-title">SPOKEASY</h5>
        <p class="address">123 Olive Dr. Fresno CA 93728</p>
        <img class="image1" src="<?php the_field('card_image_6'); ?>" class="card-img-top" alt="...">
    <div class="card-body">
        <p class="item">YOGA</p>
        <p class="card-text">Some quick example text to build on the card title and make up the bulk of the card's content.</p>
        <p class="time"> <b> 11am - 2pm </b> </p>
        <a href="#" class="button4">SEE LINEUP</a>
    </div>
    </div>
        </div>
            </div>
        </div>
        <button class="carousel-control-prev" type="button" data-bs-target="#porch_carousel" data-bs-slide="prev" id="test_prev">
            <span class="carousel-control-prev-icon" aria-hidden="true"></span>
            <span class="visually-hidden">Previous</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#porch_carousel" data-bs-slide="next" id="test_next">
        <span class="carousel-control-next-icon" aria-hidden="true"></span>
        <span class="visually-hidden">Next</span>
        </button>
    </div>
</div>
<!-- End of second featurette -->

<!-- Start of third featurette -->
<div class="row featurette">
      <div class="col-md-78">
        <h2 class="featurette-heading"><?php the_field('heading_part_1'); ?> <br> <?php the_field('heading_part_2'); ?></h2>
        <p class="lead"><?php the_field('paragraph_part_1'); ?></p>
        <a href="#" class="button2">VISIT MAP</a>
      </div>

      <div class="col-md-5">
        <!-- MAP PLACEHOLDER -->
        <img class="map" src="<?php the_field('placeholder'); ?>" width="500" height="" alt="">

      </div>
</div>
<!-- End of third featurette -->

<!-- Card section start -->
<div class="back">
    <!-- Card 1 -->
    <div class="box1">
        <img class="ticket" src="<?php the_field('ticket_image'); ?>" width="130" height="130">
        <h2 class="h22"><?php the_field('ticket_heading'); ?></h2>
        <p class="p2"> <b><?php the_field('ticket_paragraph_1'); ?></b> <?php the_field('ticket_paragraph_2'); ?></p>
        <a href="url" class="spon">BECOME A SPONSOR</a>
    </div>
    
    <!-- Card 2 -->
    <div class="box2">
        <img class="food" src="<?php the_field('food_image'); ?>" width="130" height="130">
        <h2 class="h22"><?php the_field('food_heading'); ?></h2>
        <p class="p2"><?php the_field('food_paragraph'); ?></p>
        <a href="url" class="spon">PORCHES WITH FOOD</a>
    </div>

    <!-- Card 3 -->
    <div class="box3">
        <img class="parking" src="<?php the_field('parking_image'); ?>" width="130" height="130">
        <h2 class="h22"><?php the_field('parking_heading'); ?></h2>
        <p class="p2"><?php the_field('parking_paragraph'); ?></p>
        <a href="url" class="spon3">VIEW MAP</a>
    </div>

    <!-- Card 4 -->
    <div class="box4">
        <img class="merch" src="<?php the_field('merch_image'); ?>" width="130" height="130">
        <h2 class="h22"><?php the_field('merch_heading'); ?></h2>
        <p class="p2"><?php the_field('merch_paragraph'); ?></p>
        <a href="url" class="spon4">MERCH STORE</a>
    </div>
</div>
<!-- Card section end -->

<!-- Start of forth featurette -->
<div class="row featurette">
      <div class="col-md-71">
        <h2 class="featurette-heading3"><?php the_field('sponsors_heading'); ?></h2>
        <p class="lead3"><?php the_field('sponsors_paragraph'); ?></p>
        <a href="#" class="button3">BECOME A SPONSOR</a>
      </div>
        <br>
      <div class="col-md-58">
        <div class="grid">
            <div class="one">
                <img src="<?php the_field('ragging'); ?>" alt="Ragging" >
            </div>

            <div class="two">
                <img src="<?php the_field('spokeasy'); ?>" alt="Spokeasy" >
            </div>

            <div class="three">
                <img src="<?php the_field('neighborhood_thrift'); ?>" alt="Neighborhood Thrift" >
            </div>

            <div class="four">
                <img src="<?php the_field('hi-top_coffee'); ?>" alt="Hi-top Coffee" >
            </div>
            <div class=" five">
                <img src="<?php the_field('the_brass_unicorn'); ?>" alt="The Brass Unicorn" >
            </div>
            
            <div class="six">
                <img src="<?php the_field('cf'); ?>" alt="CF" >
            </div>

            <div class="seven">
                <img src="<?php the_field('blkmktplc'); ?>" alt="Blkmktplc" >
            </div>
        </div>
        

      </div>
</div>
<!-- End of forth featurette -->


<?php get_footer(); ?>
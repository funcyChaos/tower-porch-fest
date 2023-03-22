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
        <img class="top" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-11.jpg" alt="">
        <div class="center1">FRESNO, CA</div>
        <div class="center2">APRIL 29, 2023</div>
        <div class="center3">FREE MUSIC AND ART COMMUNITY FESTIVAL</div>
        <div class="center4">COUNTDOWN</div>
</div>

<img class="middle" src="http://towerpf-site.local/wp-content/uploads/2023/03/Polygon-1.png" alt="">

<img class="bottom" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-2.jpg" alt="">

<!-- Start of first featurette -->
<div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading">BUILDING COMMUNITY <br> THROUGH MUSIC AND ART</h2>
        <p class="lead">Porches throughout Fresno's historic Tower neighborhood host performances in a day of revelry celebrating local music and art! We do this through the collaboration of Tower residents who provide venue for local musicians to perform.</p>
        <a href="#" class="button">VIEW PORCH MAP</a>
      </div>

      <div class=" row4 col-md-5">
        <img class="img1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-6.jpg" width="500" height="500" alt="">
        <img class="img2" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-7.jpg" width="248" height="248" alt="">
        <img class="img3" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-8.jpg" width="248" height="248" alt="">

      </div>
</div>
<!-- End of first featurette --25
<!-- Start of second featurette -->
<div class="row featurette color">
        <div class="col-md-7">
            <h2 class="featurette-heading2">COME BACK FOR MORE PORCHES</h2>
            <p class="lead2">Do you have a porch in the Tower that would make a sweet stage?
Tower Porchfest 2023 will happen on Saturday, April 29 from noon to 8 PM in Fresno’s Tower District neighborhood! Porch Host signups are open now through March 6, 2023.</p>
            <a href="#" class="button">VIEW ALL PORCHES</a>
        </div>

        <div class="col-md-5">
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
                    <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image.jpg" class="card-img-top" alt="...">
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
        <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image-1.jpg" class="card-img-top" alt="...">
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
        <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image-2.jpg" class="card-img-top" alt="...">
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
        <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image-3.jpg" class="card-img-top" alt="...">
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
        <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image-4.jpg" class="card-img-top" alt="...">
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
        <img class="image1" src="http://towerpf-site.local/wp-content/uploads/2023/03/Porch-Image-5.jpg" class="card-img-top" alt="...">
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
      <div class="col-md-7">
        <h2 class="featurette-heading">TOWER PORCHFEST <br> MAP</h2>
        <p class="lead">Tower Porchfest takes place in the Historic Tower District neighborhood in Fresno, California. Made up of an incredible array of classic housing types - ranging from granny flats, townhouses, and apartments to craftsman bungalows and mansions.</p>
        <a href="#" class="button2">VISIT MAP</a>
      </div>

      <div class="col-md-5">
        <!-- MAP PLACEHOLDER -->
        <img class="map" src="http://towerpf-site.local/wp-content/uploads/2023/03/Rectangle-6-1.jpg" width="500" height="" alt="">

      </div>
</div>
<!-- End of third featurette -->

<!-- Card section start -->
<div class="back">
    <!-- Card 1 -->
    <div class="box1">
        <img class="ticket" src="http://towerpf-site.local/wp-content/uploads/2023/03/Group-2.png" width="130" height="130">
        <h2 class="h22">TICKETS</h2>
        <p class="p2"> <b>No ticket required!</b> The event is open to all and totally free. We all participate as volunteers and all give our time, talents, resources, and hospitality as a gift to the neighborhood and to each other.</p>
        <a href="url" class="spon">BECOME A SPONSOR</a>
    </div>
    
    <!-- Card 2 -->
    <div class="box2">
        <img class="food" src="http://towerpf-site.local/wp-content/uploads/2023/03/Group-2-2.png" width="130" height="130">
        <h2 class="h22">FOOD & DRINKS</h2>
        <p class="p2"> This is a no-alcohol, family-friendly event. ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation uut aliquip ex ea commodo consequat...</p>
        <a href="url" class="spon">PORCHES WITH FOOD</a>
    </div>

    <!-- Card 3 -->
    <div class="box3">
        <img class="parking" src="http://towerpf-site.local/wp-content/uploads/2023/03/Group-2-1.png" width="130" height="130">
        <h2 class="h22">PARKING</h2>
        <p class="p2">Biking, walking, skateboarding, skipping & dancing from porch to porch is encouraged since parking is limited. ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation uut aliquip ex</p>
        <a href="url" class="spon3">VIEW MAP</a>
    </div>

    <!-- Card 4 -->
    <div class="box4">
        <img class="merch" src="http://towerpf-site.local/wp-content/uploads/2023/03/Group-2-3.png" width="130" height="130">
        <h2 class="h22">MERCH</h2>
        <p class="p2">Tower PorchFest merch will be available at the info booth day of. All purchases go back towards supporting this annual event. If you would like to pre-order a t-shirt please tap the link below.</p>
        <a href="url" class="spon4">MERCH STORE</a>
    </div>
</div>
<!-- Card section end -->

<!-- Start of forth featurette -->
<div class="row featurette">
      <div class="col-md-7">
        <h2 class="featurette-heading3">SPONSOR</h2>
        <p class="lead">Last year we had 60 porches with over 130 performances. There were over 5,000 views of the interactive map and our Social Media grew to 2,000+ followers (and counting). On behalf of the Tower Porchfest Committee, we ask you to consider sponsoring this awesome event!</p>
        <a href="#" class="button3">BECOME A SPONSOR</a>
      </div>

      <div class="col-md-5">
        <div class="grid">
            <div class="one">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/raging-records-logo-1.png" alt="Ragging" >
            </div>

            <div class="two">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/spokeasy-logo-1.png" alt="Spokeasy" >
            </div>

            <div class="three">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/neighborhood-thrift-1.png" alt="Neighborhood Thrift" >
            </div>

            <div class="four">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/hi-top-coffee-1.png" alt="Hi-top Coffe" >
            </div>
            <div class=" five">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/the-brass-unicorn-1.png" alt="The Brass Unicorn" >
            </div>
            
            <div class="six">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/image-1.png" alt="CF" >
            </div>

            <div class="seven">
                <img src="http://towerpf-site.local/wp-content/uploads/2023/03/blkmktplc-1.png" alt="Blkmktplc" >
            </div>
        </div>
        

      </div>
</div>
<!-- End of forth featurette -->


<?php get_footer(); ?>
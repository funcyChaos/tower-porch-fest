<?php
/**
 * Template part for displaying the icons + cards section on the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package towerpf-site
 */

?>
  <!-- ACFs for Icons and Cards -->
    <!-- card 1 -->
    <?php if( have_rows('card_with_icon_1') ): ?>
      <?php while( have_rows('card_with_icon_1') ): the_row();
      $icon = get_sub_field('icon');
      $title = get_sub_field('title');
      $paragraph = get_sub_field('paragraph');
      $link = get_sub_field('link');
      $link_text = get_sub_field('link_text'); 
        
      ?>
    <div class="col-sm-6 col-lg-3 mb-4 card-style">
        <div class="square">
          <img class="icon" src="<?php echo $icon;?>" alt="">
        </div>
        <div class="content-wrapper container-fluid">
          <h2><? echo $title;?></h2>
          <p><? echo $paragraph;?></p>
          <a class="text-link" href="<? echo $link;?>"><? echo $link_text;?></a>
        </div>
    </div>
      <?php endwhile; ?>
    <?php endif; ?>
    <!-- card 2 -->
    <?php if( have_rows('card_with_icon_2') ): ?>
      <?php while( have_rows('card_with_icon_2') ): the_row();
      $icon = get_sub_field('icon');
      $title = get_sub_field('title');
      $paragraph = get_sub_field('paragraph');
      $link = get_sub_field('link');
      $link_text = get_sub_field('link_text'); 
        
      ?>
    <div class="col-sm-6 col-lg-3 mb-4 card-style">
        <div class="square">
          <img class="icon" src="<?php echo $icon;?>" alt="">
        </div>
        <div class="content-wrapper container-fluid">
          <h2><? echo $title;?></h2>
          <p class="max-char"><? echo $paragraph;?></p>
          <a class="text-link" href="<? echo $link;?>"><? echo $link_text;?></a>
        </div>
    </div>
      <?php endwhile; ?>
    <?php endif; ?>
    <!-- card 3 -->
    <?php if( have_rows('card_with_icon_3') ): ?>
      <?php while( have_rows('card_with_icon_3') ): the_row();
      $icon = get_sub_field('icon');
      $title = get_sub_field('title');
      $paragraph = get_sub_field('paragraph');
      $link = get_sub_field('link');
      $link_text = get_sub_field('link_text'); 
        
      ?>
    <div class="col-sm-6 col-lg-3 mb-4 card-style">
        <div class="square">
          <img class="icon" src="<?php echo $icon;?>" alt="">
        </div>
        <div class="content-wrapper container-fluid">
          <h2><? echo $title;?></h2>
          <p class="max-char"><? echo $paragraph;?></p>
          <a class="text-link" href="<? echo $link;?>"><? echo $link_text;?></a>
        </div>
    </div>
      <?php endwhile; ?>
    <?php endif; ?>
    <!-- card 4 -->
    <?php if( have_rows('card_with_icon_4') ): ?>
      <?php while( have_rows('card_with_icon_4') ): the_row();
      $icon = get_sub_field('icon');
      $title = get_sub_field('title');
      $paragraph = get_sub_field('paragraph');
      $link = get_sub_field('link');
      $link_text = get_sub_field('link_text'); 
        
      ?>
    <div class="col-sm-6 col-lg-3 mb-4 card-style">
        <div class="square">
          <img class="icon" src="<?php echo $icon;?>" alt="">
        </div>
        <div class="content-wrapper container-fluid">
          <h2><? echo $title;?></h2>
          <p><? echo $paragraph;?></p>
          <a class="text-link" href="<? echo $link;?>"><? echo $link_text;?></a>
        </div>
    </div>
      <?php endwhile; ?>
    <?php endif; ?>

    
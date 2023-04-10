<?php
/**
 * Template part for displaying the icons + cards section on the homepage
 *
 * @link https://developer.wordpress.org/themes/basics/template-hierarchy/
 *
 * @package towerpf-site
 */

?>

<?php if( have_rows('sponsor_tier_1') ): ?>
    <?php while( have_rows('sponsor_tier_1') ): the_row(); 

      $tier_1_title = get_sub_field('tier_1_title');
      $sponsor_logo_1 = get_sub_field('sponsor_logo_1'); 
      $sponsor_logo_2 = get_sub_field('sponsor_logo_2');
      $sponsor_logo_3 = get_sub_field('sponsor_logo_3');
      $sponsor_logo_4 = get_sub_field('sponsor_logo_4');
      $sponsor_logo_5 = get_sub_field('sponsor_logo_5');
      $sponsor_logo_6 = get_sub_field('sponsor_logo_6');
      $sponsor_logo_7 = get_sub_field('sponsor_logo_7');
      $sponsor_logo_8 = get_sub_field('sponsor_logo_8');
      $sponsor_logo_9 = get_sub_field('sponsor_logo_9');
      $sponsor_logo_10 = get_sub_field('sponsor_logo_10');
      ?>

        <h2><? echo $tier_1_title?></h2>
          <div class="logo-container"> 
          <!-- Checking $sponsor_logo_X is an empty variable. If it is empty the img element will not display. 
          For example we gave the user 10 fields to add sponsor logos to in ACF, but they only have 3. That means
          the 7 other img tags will not be visible until a logo is added  -->
          <div class="logo-container"> 
          <?php if (!empty($sponsor_logo_1)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_1;?>" alt="">
          <?php endif; ?>

          <?php if (!empty($sponsor_logo_2)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_2;?>" alt="">
          <?php endif; ?>

          <?php if (!empty($sponsor_logo_3)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_3;?>" alt="">
          <?php endif; ?>
          <?php if (!empty($sponsor_logo_4)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_4;?>" alt="">
          <?php endif; ?>
          <?php if (!empty($sponsor_logo_5)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_5;?>" alt="">
          <?php endif; ?>
          <?php if (!empty($sponsor_logo_6)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_6;?>" alt="">
          <?php endif; ?>
            <?php if (!empty($sponsor_logo_7)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_7;?>" alt="">
          <?php endif; ?>
            <?php if (!empty($sponsor_logo_8)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_8;?>" alt="">
          <?php endif; ?>
            <?php if (!empty($sponsor_logo_9)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_9;?>" alt="">
          <?php endif; ?>
            <?php if (!empty($sponsor_logo_10)): ?>
            <img class="logo-style" src="<?php echo $sponsor_logo_10;?>" alt="">
          <?php endif; ?>
          </div>
        </div>
    <?php endwhile; ?>
  <?php endif; ?>

  <?php if( have_rows('sponsor_tier_2') ): ?>
    <?php while( have_rows('sponsor_tier_2') ): the_row(); 

      $tier_2_title = get_sub_field('tier_2_title');
      $sponsor_logo_1 = get_sub_field('sponsor_logo_1'); 
      $sponsor_logo_2 = get_sub_field('sponsor_logo_2');
      $sponsor_logo_3 = get_sub_field('sponsor_logo_3');
      $sponsor_logo_4 = get_sub_field('sponsor_logo_4');
      $sponsor_logo_5 = get_sub_field('sponsor_logo_5');
      $sponsor_logo_6 = get_sub_field('sponsor_logo_6');
      $sponsor_logo_7 = get_sub_field('sponsor_logo_7');
      $sponsor_logo_8 = get_sub_field('sponsor_logo_8');
      $sponsor_logo_9 = get_sub_field('sponsor_logo_9');
      $sponsor_logo_10 = get_sub_field('sponsor_logo_10');
      ?>

      <h2><? echo $tier_2_title?></h2>
        <div class="logo-container"> 
        <?php if (!empty($sponsor_logo_1)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_1;?>" alt="">
        <?php endif; ?>

        <?php if (!empty($sponsor_logo_2)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_2;?>" alt="">
        <?php endif; ?>

        <?php if (!empty($sponsor_logo_3)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_3;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_4)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_4;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_5)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_5;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_6)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_6;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_7)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_7;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_8)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_8;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_9)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_9;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_10)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_10;?>" alt="">
        <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>


  <?php if( have_rows('sponsor_tier_3') ): ?>
    <?php while( have_rows('sponsor_tier_3') ): the_row(); 

      $tier_3_title = get_sub_field('tier_3_title');
      $sponsor_logo_1 = get_sub_field('sponsor_logo_1'); 
      $sponsor_logo_2 = get_sub_field('sponsor_logo_2');
      $sponsor_logo_3 = get_sub_field('sponsor_logo_3');
      $sponsor_logo_4 = get_sub_field('sponsor_logo_4');
      $sponsor_logo_5 = get_sub_field('sponsor_logo_5');
      $sponsor_logo_6 = get_sub_field('sponsor_logo_6');
      $sponsor_logo_7 = get_sub_field('sponsor_logo_7');
      $sponsor_logo_8 = get_sub_field('sponsor_logo_8');
      $sponsor_logo_9 = get_sub_field('sponsor_logo_9');
      $sponsor_logo_10 = get_sub_field('sponsor_logo_10');
      ?>

      <h2><? echo $tier_3_title?></h2>
        <div class="logo-container"> 
        <?php if (!empty($sponsor_logo_1)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_1;?>" alt="">
        <?php endif; ?>

        <?php if (!empty($sponsor_logo_2)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_2;?>" alt="">
        <?php endif; ?>

        <?php if (!empty($sponsor_logo_3)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_3;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_4)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_4;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_5)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_5;?>" alt="">
        <?php endif; ?>
        <?php if (!empty($sponsor_logo_6)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_6;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_7)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_7;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_8)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_8;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_9)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_9;?>" alt="">
        <?php endif; ?>
          <?php if (!empty($sponsor_logo_10)): ?>
          <img class="logo-style" src="<?php echo $sponsor_logo_10;?>" alt="">
        <?php endif; ?>
        </div>
      </div>
    <?php endwhile; ?>
  <?php endif; ?>
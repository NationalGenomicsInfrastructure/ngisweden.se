<?php
$alert_box = false;
if(get_theme_mod( 'ngisweden_banner_message_text' ) && strlen(trim(get_theme_mod( 'ngisweden_banner_message_text' ))) > 0) {
  $alert_box = true;
}

get_header();
echo do_shortcode('[image-carousel]');
?>

<div class="container main-page" id="front-page-container">
  <?php

  if (have_posts()) {
    while (have_posts()) {
      the_post();
      the_content();
    }
  }
  ?>
</div>


<?php get_footer(); ?>

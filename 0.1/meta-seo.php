<?php
   /*
   Plugin Name: META SEO
   Description: This plugin adds meta description tags on posts and pages based on your content.
   Version: 0.1
   Author: Rajko Orman
   Author URI: http://manornk.me
   License: GPL2
   */

add_action('wp_head','manornk_add_meta_data');


function manornk_add_meta_data() {
  if(is_page() || is_single()) {
    while ( have_posts() ) : the_post();

    $excerpt = trim(get_the_excerpt($post));
    if(strlen($excerpt) >= 155) {
      $excerpt = substr($excerpt, 0, 155);
    }
    $meta="<meta name='description' content='" . $excerpt . "' />";
    echo $meta;

    endwhile;
  } else {
      $title = wp_title('|', false);
      $meta="<meta name='description' content='" . $title ."' />";
    echo $meta;

  }
}

?>

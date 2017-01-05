<?php

class ManornkMetaSeo {

  public function init() {
    $class = __CLASS__;
    new $class;
    unset($class);
  }
  public function __construct() {
      add_action('wp_head', array( $this, 'manornk_add_meta_data'));
  }

  public function manornk_add_meta_data() {
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
        $title = get_bloginfo('title');
        $description = get_bloginfo('description');
        $meta="<meta name='description' content='" . $title ." | " . $description . "' />";
      echo $meta;

    }
  }

}

?>

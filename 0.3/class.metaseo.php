<?php

class ManornkMetaSeo {

  public function init() {
    $class = __CLASS__;
    new $class;
    unset($class);
  }
  public function __construct() {
      add_action('wp_head', array( $this, 'manornk_add_meta_data'));
      add_action('admin_menu', array( $this, 'meta_seo_plugin_page'));
      add_action('admin_init', array($this, 'plugin_admin_init'));

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
        $options = get_option('manornk_meta_description');
          if(isset($options) && $options != "" ) {
            $meta="<meta name='description' content='" . $options . "' />";
          } else {
            $meta="<meta name='description' content='" . $title ." | " . $description . "' />";
          }
        echo $meta;
    }
  }

/* add_settings_section */


  function meta_seo_plugin_page() {
    add_menu_page('Meta SEO', 'Meta SEO', 'manage_options', 'manornk-meta-seo', array($this, 'meta_seo_page')); //last function
  }

  // display the admin options page
  function meta_seo_page() { ?>
    <div>
      <h1>Meta SEO</h1>
      Options related to the <a target="_blank" href="https://wordpress.org/plugins/meta-seo/">Meta SEO</a>.
      <form action="options.php" method="post">
        <?php settings_fields('manornk_meta_description'); ?>
        <?php do_settings_sections('plugin'); ?>

        <?php submit_button(); ?>
      </form>
    </div>
    <?php
  }


  function plugin_admin_init(){
    register_setting( 'manornk_meta_description', 'manornk_meta_description', 'plugin_options_validate' );
    add_settings_section('plugin_main', 'Settings', array($this, 'plugin_section_text'), 'plugin');
    add_settings_field('plugin_text_string', 'HomePage: ', array($this, 'plugin_setting_string'), 'plugin', 'plugin_main');
  }

  function plugin_section_text() {
    echo '<p>Insert meta description.</p>';
  }

  function plugin_setting_string() {
    $options = get_option('manornk_meta_description');
    echo "<input id='plugin_text_string' name='manornk_meta_description' size='40' type='text' value='{$options}' />";
  }

}

?>

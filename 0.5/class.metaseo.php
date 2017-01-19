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
      remove_action('wp_head', 'meta_description');
      add_action("add_meta_boxes", array($this, "manornk_add_meta_description_meta_box"));
      add_action("save_post", array($this, "manornk_save_meta_description_link"));
      remove_action('wp_head', 'rel_canonical');
      add_action("add_meta_boxes", array($this, "manornk_add_rel_canonical_meta_box"));
      add_action("save_post", array($this, "manornk_save_rel_canonical_link"));
  }

  public function manornk_add_meta_data() {
    if(is_page() || is_single()) {

      while ( have_posts() ) : the_post();
	global $post;
	$manornk_id = $post->ID;
	$content = get_post_meta($manornk_id, '_manornk_meta_description_value_key', true);
	echo get_post_meta($post_id, '_manornk_meta_description_value_key', true);
	if (! empty($content)) {
		echo "<meta name='description' content='" . $content . "' />";
	} else {

        	$excerpt = trim(get_the_excerpt($post));
       	        if(strlen($excerpt) >= 155) {
       	            $excerpt = substr($excerpt, 0, 155);
       	        }
       	 	$meta="<meta name='description' content='" . $excerpt . "' />";
       	        echo $meta;
	}

  $canonical= get_post_meta($manornk_id, '_manornk_rel_canonical_value_key', true);
  if($canonical != "") {
    echo '<link rel="canonical" href="' . $canonical . '" />';
  }

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




  function manornk_add_meta_description_meta_box() {
	add_meta_box( 'manornk_meta_description', 'Meta', array($this, 'manornk_meta_description_callback'), array('post', 'page'), 'normal' );
  }
  function manornk_meta_description_callback( $post ) {
	wp_nonce_field( 'manornk_save_meta_description_link', 'manornk_meta_description_meta_box_nonce' );
	$value = get_post_meta( $post->ID, '_manornk_meta_description_value_key', true );

	echo '<label for="manornk_meta_description_field">Meta Description: </lable>';
	echo '<input type="text" id="manornk_meta_description_field" name="manornk_meta_description_field" value="' . esc_attr( $value ) . '" size="50" />';
  }



   function manornk_save_meta_description_link( $post_id ) {

	if( ! isset( $_POST['manornk_meta_description_meta_box_nonce'] ) ){
		return;
	}

	if( ! wp_verify_nonce( $_POST['manornk_meta_description_meta_box_nonce'], 'manornk_save_meta_description_link') ) {
		return;
	}

	if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
		return;
	}

	if( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	if( ! isset( $_POST['manornk_meta_description_field'] ) ) {
		return;
	}

	$my_data = sanitize_text_field( $_POST['manornk_meta_description_field'] );
	update_post_meta( $post_id, '_manornk_meta_description_value_key', $my_data );

  }

  //canonical

  function manornk_add_rel_canonical_meta_box() {
  add_meta_box( 'manornk_rel_canonical', 'Meta', array($this, 'manornk_rel_canonical_callback'), array('post', 'page'), 'normal' );
  }

  function manornk_rel_canonical_callback( $post ) {
  wp_nonce_field( 'manornk_save_rel_canonical_link', 'manornk_rel_canonical_meta_box_nonce' );

  $value = get_post_meta( $post->ID, '_manornk_rel_canonical_value_key', true );

  echo '<label for="manornk_rel_canonical_field">Canonical: </lable>';
  echo '<input type="text" id="manornk_rel_canonical_field" name="manornk_rel_canonical_field" value="' . esc_attr( $value ) . '" size="50" />';
  }



   function manornk_save_rel_canonical_link( $post_id ) {

  if( ! isset( $_POST['manornk_rel_canonical_meta_box_nonce'] ) ){
    return;
  }

  if( ! wp_verify_nonce( $_POST['manornk_rel_canonical_meta_box_nonce'], 'manornk_save_rel_canonical_link') ) {
    return;
  }

  if( defined('DOING_AUTOSAVE') && DOING_AUTOSAVE ){
    return;
  }

  if( ! current_user_can( 'edit_post', $post_id ) ) {
    return;
  }

  if( ! isset( $_POST['manornk_rel_canonical_field'] ) ) {
    return;
  }

  $my_data = sanitize_text_field( $_POST['manornk_rel_canonical_field'] );
  update_post_meta( $post_id, '_manornk_rel_canonical_value_key', $my_data );

  }
}

?>

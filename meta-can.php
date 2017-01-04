add_action("add_meta_boxes", "manornk_add_rel_canonical_meta_box");

function manornk_add_rel_canonical_meta_box() {
	add_meta_box( 'rel_canonical', 'Canonical Link', 'manornk_rel_canonical_meta_box_callback', 'post', 'side' );
}
function manornk_rel_canonical_meta_box_callback( $post ) {
	wp_nonce_field( 'manornk_save_rel_canonical_link', 'manornk_rel_canonical_meta_box_nonce' );
	
	$value = get_post_meta( $post->ID, '_rel_canonical_value_key', true );
	
	echo '<label for="manornk_rel_canonical_field">User Email Address: </lable>';
	echo '<input type="email" id="manornk_rel_canonical_field" name="manornk_rel_canonical_field" value="' . esc_attr( $value ) . '" size="25" />';
}

add_action("save_meta_box_rel_canonical", "manornk_save_rel_canonical_link");

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
	
	update_post_meta( $post_id, '_rel_canonical_value_key', $my_data );
	
}

//get_post_meta($post->ID, '_manornk_meta_canonical_box_value_key', true);
<link href="http://www.example.com/canonical-version-of-page/" rel="canonical" />



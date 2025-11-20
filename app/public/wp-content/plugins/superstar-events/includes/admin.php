<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Add Meta Boxes
 */
function superstar_add_event_metaboxes() {
	add_meta_box(
		'superstar_event_details',
		'Event Details',
		'superstar_render_event_metabox',
		'event',
		'normal',
		'high'
	);
}
add_action( 'add_meta_boxes', 'superstar_add_event_metaboxes' );

/**
 * Render Meta Box Content
 */
function superstar_render_event_metabox( $post ) {
	// Retrieve existing values
	$start_date = get_post_meta( $post->ID, '_superstar_start_date', true );
	$end_date   = get_post_meta( $post->ID, '_superstar_end_date', true );
	$address    = get_post_meta( $post->ID, '_superstar_address', true );
	$lat        = get_post_meta( $post->ID, '_superstar_lat', true );
	$lng        = get_post_meta( $post->ID, '_superstar_lng', true );

	// Nonce field for security
	wp_nonce_field( 'superstar_save_event_details', 'superstar_event_nonce' );
	?>
	<div class="superstar-metabox-wrapper">
		<p>
			<label for="superstar_start_date"><strong>Start Date:</strong></label><br>
			<input type="datetime-local" id="superstar_start_date" name="superstar_start_date" value="<?php echo esc_attr( $start_date ); ?>" class="widefat">
		</p>
		<p>
			<label for="superstar_end_date"><strong>End Date:</strong></label><br>
			<input type="datetime-local" id="superstar_end_date" name="superstar_end_date" value="<?php echo esc_attr( $end_date ); ?>" class="widefat">
		</p>
		<p>
			<label for="superstar_address"><strong>Address:</strong></label><br>
			<input type="text" id="superstar_address" name="superstar_address" value="<?php echo esc_attr( $address ); ?>" class="widefat">
		</p>
		<p>
			<label><strong>Map Coordinates:</strong></label><br>
			<label for="superstar_lat">Latitude:</label>
			<input type="text" id="superstar_lat" name="superstar_lat" value="<?php echo esc_attr( $lat ); ?>">
			<label for="superstar_lng">Longitude:</label>
			<input type="text" id="superstar_lng" name="superstar_lng" value="<?php echo esc_attr( $lng ); ?>">
			<br>
			<small><em>Enter coordinates manually or use the map below (coming soon).</em></small>
		</p>
	</div>
	<?php
}

/**
 * Save Meta Box Data
 */
function superstar_save_event_meta( $post_id ) {
	// Check nonce
	if ( ! isset( $_POST['superstar_event_nonce'] ) || ! wp_verify_nonce( $_POST['superstar_event_nonce'], 'superstar_save_event_details' ) ) {
		return;
	}

	// Check autosave
	if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
		return;
	}

	// Check permissions
	if ( ! current_user_can( 'edit_post', $post_id ) ) {
		return;
	}

	// Save fields
	if ( isset( $_POST['superstar_start_date'] ) ) {
		update_post_meta( $post_id, '_superstar_start_date', sanitize_text_field( $_POST['superstar_start_date'] ) );
	}
	if ( isset( $_POST['superstar_end_date'] ) ) {
		update_post_meta( $post_id, '_superstar_end_date', sanitize_text_field( $_POST['superstar_end_date'] ) );
	}
	if ( isset( $_POST['superstar_address'] ) ) {
		update_post_meta( $post_id, '_superstar_address', sanitize_text_field( $_POST['superstar_address'] ) );
	}
	if ( isset( $_POST['superstar_lat'] ) ) {
		update_post_meta( $post_id, '_superstar_lat', sanitize_text_field( $_POST['superstar_lat'] ) );
	}
	if ( isset( $_POST['superstar_lng'] ) ) {
		update_post_meta( $post_id, '_superstar_lng', sanitize_text_field( $_POST['superstar_lng'] ) );
	}
}
add_action( 'save_post', 'superstar_save_event_meta' );

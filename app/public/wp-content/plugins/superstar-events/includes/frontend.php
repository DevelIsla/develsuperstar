<?php
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Register Shortcode [superstar_events]
 */
function superstar_events_shortcode( $atts ) {
	ob_start();
	include SUPERSTAR_EVENTS_PATH . 'templates/archive-event.php';
	return ob_get_clean();
}
add_shortcode( 'superstar_events', 'superstar_events_shortcode' );

/**
 * AJAX Handler for Filtering Events
 */
function superstar_filter_events() {
	check_ajax_referer( 'superstar_events_nonce', 'nonce' );

	$category = isset( $_POST['category'] ) ? sanitize_text_field( $_POST['category'] ) : '';
	$date_start = isset( $_POST['date_start'] ) ? sanitize_text_field( $_POST['date_start'] ) : '';
	$date_end = isset( $_POST['date_end'] ) ? sanitize_text_field( $_POST['date_end'] ) : '';
	$search_query = isset( $_POST['search'] ) ? sanitize_text_field( $_POST['search'] ) : '';

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		's'              => $search_query,
		'meta_query'     => array( 'relation' => 'AND' ),
		'tax_query'      => array( 'relation' => 'AND' ),
	);

	// Date Filtering
	if ( $date_start ) {
		$args['meta_query'][] = array(
			'key'     => '_superstar_start_date',
			'value'   => $date_start,
			'compare' => '>=',
			'type'    => 'DATETIME'
		);
	}
	if ( $date_end ) {
		$args['meta_query'][] = array(
			'key'     => '_superstar_end_date',
			'value'   => $date_end,
			'compare' => '<=',
			'type'    => 'DATETIME'
		);
	}

	// Category Filtering
	if ( $category ) {
		$args['tax_query'][] = array(
			'taxonomy' => 'event_category',
			'field'    => 'slug',
			'terms'    => $category,
		);
	}

	$query = new WP_Query( $args );

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$start_date = get_post_meta( get_the_ID(), '_superstar_start_date', true );
			$address = get_post_meta( get_the_ID(), '_superstar_address', true );
			?>
			<div class="superstar-event-item">
				<div class="superstar-event-image">
					<?php if ( has_post_thumbnail() ) : ?>
						<a href="<?php the_permalink(); ?>"><?php the_post_thumbnail( 'medium' ); ?></a>
					<?php endif; ?>
				</div>
				<div class="superstar-event-content">
					<h3><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h3>
					<p class="superstar-event-meta">
						<?php if ( $start_date ) : ?>
							<span class="event-date"><i class="dashicons dashicons-calendar"></i> <?php echo date( 'F j, Y g:i a', strtotime( $start_date ) ); ?></span>
						<?php endif; ?>
						<?php if ( $address ) : ?>
							<span class="event-address"><i class="dashicons dashicons-location"></i> <?php echo esc_html( $address ); ?></span>
						<?php endif; ?>
					</p>
					<div class="superstar-event-excerpt"><?php the_excerpt(); ?></div>
				</div>
			</div>
			<?php
		}
		wp_reset_postdata();
	} else {
		echo '<p class="no-events">No events found matching your criteria.</p>';
	}

	wp_die();
}
add_action( 'wp_ajax_superstar_filter_events', 'superstar_filter_events' );
add_action( 'wp_ajax_nopriv_superstar_filter_events', 'superstar_filter_events' );

/**
 * AJAX Handler for Calendar Events
 */
function superstar_get_calendar_events() {
	check_ajax_referer( 'superstar_events_nonce', 'nonce' );

	$month = isset( $_POST['month'] ) ? intval( $_POST['month'] ) : date( 'n' );
	$year  = isset( $_POST['year'] ) ? intval( $_POST['year'] ) : date( 'Y' );

	// Calculate start and end of the month
	$start_date = date( 'Y-m-d H:i:s', mktime( 0, 0, 0, $month, 1, $year ) );
	$end_date   = date( 'Y-m-d H:i:s', mktime( 23, 59, 59, $month + 1, 0, $year ) );

	$args = array(
		'post_type'      => 'event',
		'posts_per_page' => -1,
		'post_status'    => 'publish',
		'meta_query'     => array(
			'relation' => 'OR',
			array(
				'key'     => '_superstar_start_date',
				'value'   => array( $start_date, $end_date ),
				'compare' => 'BETWEEN',
				'type'    => 'DATETIME'
			),
			array(
				'key'     => '_superstar_end_date',
				'value'   => array( $start_date, $end_date ),
				'compare' => 'BETWEEN',
				'type'    => 'DATETIME'
			)
		)
	);

	$query = new WP_Query( $args );
	$events = array();

	if ( $query->have_posts() ) {
		while ( $query->have_posts() ) {
			$query->the_post();
			$start = get_post_meta( get_the_ID(), '_superstar_start_date', true );
			$end   = get_post_meta( get_the_ID(), '_superstar_end_date', true );
			
			$events[] = array(
				'id'    => get_the_ID(),
				'title' => get_the_title(),
				'start' => $start,
				'end'   => $end,
				'url'   => get_permalink()
			);
		}
		wp_reset_postdata();
	}

	wp_send_json_success( $events );
}
add_action( 'wp_ajax_superstar_get_calendar_events', 'superstar_get_calendar_events' );
add_action( 'wp_ajax_nopriv_superstar_get_calendar_events', 'superstar_get_calendar_events' );


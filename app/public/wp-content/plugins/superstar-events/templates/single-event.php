<?php
get_header();

while ( have_posts() ) :
	the_post();
	$start_date = get_post_meta( get_the_ID(), '_superstar_start_date', true );
	$end_date   = get_post_meta( get_the_ID(), '_superstar_end_date', true );
	$address    = get_post_meta( get_the_ID(), '_superstar_address', true );
	$lat        = get_post_meta( get_the_ID(), '_superstar_lat', true );
	$lng        = get_post_meta( get_the_ID(), '_superstar_lng', true );
	?>

	<div class="superstar-single-event-container">
		
		<div class="superstar-event-header">
			<h1><?php the_title(); ?></h1>
			<?php if ( has_post_thumbnail() ) : ?>
				<div class="superstar-event-featured-image">
					<?php the_post_thumbnail( 'large' ); ?>
				</div>
			<?php endif; ?>
		</div>

		<div class="superstar-event-body">
			<div class="superstar-event-details">
				<h2>Event Details</h2>
				<ul>
					<?php if ( $start_date ) : ?>
						<li><strong>Start:</strong> <?php echo date( 'F j, Y g:i a', strtotime( $start_date ) ); ?></li>
					<?php endif; ?>
					<?php if ( $end_date ) : ?>
						<li><strong>End:</strong> <?php echo date( 'F j, Y g:i a', strtotime( $end_date ) ); ?></li>
					<?php endif; ?>
					<?php if ( $address ) : ?>
						<li><strong>Address:</strong> <?php echo esc_html( $address ); ?></li>
					<?php endif; ?>
					<?php
					$terms = get_the_terms( get_the_ID(), 'event_category' );
					if ( $terms && ! is_wp_error( $terms ) ) :
						$cat_links = array();
						foreach ( $terms as $term ) {
							$cat_links[] = $term->name;
						}
						?>
						<li><strong>Category:</strong> <?php echo implode( ', ', $cat_links ); ?></li>
					<?php endif; ?>
				</ul>

				<div class="superstar-event-description">
					<?php the_content(); ?>
				</div>
			</div>

			<?php if ( $lat && $lng ) : ?>
				<div class="superstar-event-map">
					<h2>Location</h2>
					<div id="superstar-map" style="height: 400px; width: 100%;" data-lat="<?php echo esc_attr( $lat ); ?>" data-lng="<?php echo esc_attr( $lng ); ?>"></div>
				</div>
			<?php endif; ?>
		</div>

	</div>

	<?php
endwhile;

get_footer();

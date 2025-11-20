<div class="superstar-events-wrapper">
	
	<!-- Filters -->
	<div class="superstar-filters">
		<input type="text" id="superstar-search" placeholder="Search events...">
		
		<select id="superstar-category">
			<option value="">All Categories</option>
			<?php
			$categories = get_terms( array( 'taxonomy' => 'event_category', 'hide_empty' => false ) );
			if ( ! empty( $categories ) && ! is_wp_error( $categories ) ) {
				foreach ( $categories as $cat ) {
					echo '<option value="' . esc_attr( $cat->slug ) . '">' . esc_html( $cat->name ) . '</option>';
				}
			}
			?>
		</select>

		<input type="date" id="superstar-start-date" placeholder="Start Date">
		<input type="date" id="superstar-end-date" placeholder="End Date">
		
		<button id="superstar-filter-btn">Filter</button>
	</div>

	<!-- Calendar View -->
	<div class="superstar-calendar-view">
		<div class="calendar-header">
			<button id="prev-month">&lt;</button>
			<h2 id="current-month-label"></h2>
			<button id="next-month">&gt;</button>
		</div>
		<div class="calendar-grid" id="calendar-grid">
			<!-- Calendar days will be injected here via JS -->
		</div>
	</div>

	<!-- List View -->
	<div class="superstar-list-view" id="superstar-events-list">
		<?php
		// Initial Load
		$args = array(
			'post_type'      => 'event',
			'posts_per_page' => 10,
			'post_status'    => 'publish',
		);
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
			echo '<p>No events found.</p>';
		}
		?>
	</div>

</div>

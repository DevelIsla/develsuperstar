<?php
/**
 * Template for displaying single event
 */

get_header();

while (have_posts()) : the_post();
    $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
    $end_date = get_post_meta(get_the_ID(), '_event_end_date', true);
    $location = get_post_meta(get_the_ID(), '_event_location', true);
    $address = get_post_meta(get_the_ID(), '_event_address', true);
    $categories = get_the_terms(get_the_ID(), 'event-category');
?>

<article id="post-<?php the_ID(); ?>" <?php post_class('single-event'); ?>>
    <div class="event-header">
        <div class="event-header-content">
            <h1 class="event-title"><?php the_title(); ?></h1>
            
            <?php if ($categories) : ?>
                <div class="event-categories">
                    <?php foreach ($categories as $category) : ?>
                        <span class="event-category-badge"><?php echo esc_html($category->name); ?></span>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <?php if (has_post_thumbnail()) : ?>
        <div class="event-featured-image">
            <?php the_post_thumbnail('large'); ?>
        </div>
    <?php endif; ?>

    <div class="event-details-section">
        <div class="event-info-sidebar">
            <div class="event-info-box">
                <h3><?php _e('Event Information', 'develer-events'); ?></h3>
                
                <?php if ($start_date) : 
                    $start_obj = new DateTime($start_date);
                ?>
                    <div class="event-info-item">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <div>
                            <strong><?php _e('Start:', 'develer-events'); ?></strong><br>
                            <?php echo $start_obj->format('F j, Y \a\t g:i A'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($end_date) : 
                    $end_obj = new DateTime($end_date);
                ?>
                    <div class="event-info-item">
                        <span class="dashicons dashicons-calendar-alt"></span>
                        <div>
                            <strong><?php _e('End:', 'develer-events'); ?></strong><br>
                            <?php echo $end_obj->format('F j, Y \a\t g:i A'); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($location) : ?>
                    <div class="event-info-item">
                        <span class="dashicons dashicons-location"></span>
                        <div>
                            <strong><?php _e('Location:', 'develer-events'); ?></strong><br>
                            <?php echo esc_html($location); ?>
                        </div>
                    </div>
                <?php endif; ?>
                
                <?php if ($address) : ?>
                    <div class="event-info-item">
                        <span class="dashicons dashicons-location-alt"></span>
                        <div>
                            <strong><?php _e('Address:', 'develer-events'); ?></strong><br>
                            <?php echo nl2br(esc_html($address)); ?>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <?php if ($address) : ?>
                <div class="event-map">
                    <iframe 
                        width="100%" 
                        height="300" 
                        frameborder="0" 
                        style="border:0" 
                        src="https://www.google.com/maps/embed/v1/place?key=YOUR_GOOGLE_MAPS_API_KEY&q=<?php echo urlencode($address); ?>" 
                        allowfullscreen>
                    </iframe>
                    <p class="map-note"><?php _e('Note: Replace YOUR_GOOGLE_MAPS_API_KEY with your actual Google Maps API key', 'develer-events'); ?></p>
                </div>
            <?php endif; ?>
        </div>

        <div class="event-content-main">
            <h2><?php _e('About This Event', 'develer-events'); ?></h2>
            <div class="event-description">
                <?php the_content(); ?>
            </div>
        </div>
    </div>

    <div class="event-navigation">
        <?php
        $prev_post = get_previous_post();
        $next_post = get_next_post();
        ?>
        
        <?php if ($prev_post) : ?>
            <div class="nav-previous">
                <a href="<?php echo get_permalink($prev_post); ?>">
                    ← <?php _e('Previous Event', 'develer-events'); ?>
                </a>
            </div>
        <?php endif; ?>
        
        <div class="nav-archive">
            <a href="<?php echo get_post_type_archive_link('event'); ?>">
                <?php _e('All Events', 'develer-events'); ?>
            </a>
        </div>
        
        <?php if ($next_post) : ?>
            <div class="nav-next">
                <a href="<?php echo get_permalink($next_post); ?>">
                    <?php _e('Next Event', 'develer-events'); ?> →
                </a>
            </div>
        <?php endif; ?>
    </div>
</article>

<?php endwhile; ?>

<?php get_footer(); ?>

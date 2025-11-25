<?php
/**
 * Template for displaying events archive
 */

get_header();
?>

<div class="events-archive-container">
    <header class="page-header">
        <h1 class="page-title"><?php _e('Events', 'develer-events'); ?></h1>
    </header>

    <div class="events-filters">
        <form id="events-filter-form" class="filter-form">
            <div class="filter-row">
                <div class="filter-item">
                    <label for="event-search"><?php _e('Search:', 'develer-events'); ?></label>
                    <input type="text" id="event-search" name="s" placeholder="<?php _e('Search events...', 'develer-events'); ?>" value="<?php echo get_search_query(); ?>">
                </div>
                
                <div class="filter-item">
                    <label for="event-category"><?php _e('Category:', 'develer-events'); ?></label>
                    <?php
                    wp_dropdown_categories(array(
                        'taxonomy' => 'event-category',
                        'show_option_all' => __('All Categories', 'develer-events'),
                        'name' => 'event_category',
                        'id' => 'event-category',
                        'selected' => isset($_GET['event_category']) ? $_GET['event_category'] : '',
                        'hierarchical' => true,
                        'value_field' => 'term_id'
                    ));
                    ?>
                </div>
                
                <div class="filter-item">
                    <label for="event-date-from"><?php _e('From:', 'develer-events'); ?></label>
                    <input type="date" id="event-date-from" name="date_from" value="<?php echo isset($_GET['date_from']) ? esc_attr($_GET['date_from']) : ''; ?>">
                </div>
                
                <div class="filter-item">
                    <label for="event-date-to"><?php _e('To:', 'develer-events'); ?></label>
                    <input type="date" id="event-date-to" name="date_to" value="<?php echo isset($_GET['date_to']) ? esc_attr($_GET['date_to']) : ''; ?>">
                </div>
                
                <div class="filter-item">
                    <button type="submit" class="filter-button"><?php _e('Filter', 'develer-events'); ?></button>
                    <a href="<?php echo get_post_type_archive_link('event'); ?>" class="reset-button"><?php _e('Reset', 'develer-events'); ?></a>
                </div>
            </div>
        </form>
    </div>

    <div class="events-list">
        <?php if (have_posts()) : ?>
            <div class="events-grid">
                <?php while (have_posts()) : the_post(); 
                    $start_date = get_post_meta(get_the_ID(), '_event_start_date', true);
                    $location = get_post_meta(get_the_ID(), '_event_location', true);
                    $categories = get_the_terms(get_the_ID(), 'event-category');
                ?>
                    <article id="post-<?php the_ID(); ?>" <?php post_class('event-card'); ?>>
                        <?php if (has_post_thumbnail()) : ?>
                            <div class="event-thumbnail">
                                <a href="<?php the_permalink(); ?>">
                                    <?php the_post_thumbnail('medium'); ?>
                                </a>
                            </div>
                        <?php endif; ?>
                        
                        <div class="event-content">
                            <?php if ($start_date) : 
                                $date_obj = new DateTime($start_date);
                            ?>
                                <div class="event-date">
                                    <span class="event-day"><?php echo $date_obj->format('d'); ?></span>
                                    <span class="event-month"><?php echo $date_obj->format('M'); ?></span>
                                    <span class="event-year"><?php echo $date_obj->format('Y'); ?></span>
                                </div>
                            <?php endif; ?>
                            
                            <h2 class="event-title">
                                <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
                            </h2>
                            
                            <?php if ($categories) : ?>
                                <div class="event-categories">
                                    <?php foreach ($categories as $category) : ?>
                                        <span class="event-category"><?php echo esc_html($category->name); ?></span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                            
                            <?php if ($location) : ?>
                                <div class="event-location">
                                    <span class="dashicons dashicons-location"></span>
                                    <?php echo esc_html($location); ?>
                                </div>
                            <?php endif; ?>
                            
                            <div class="event-excerpt">
                                <?php the_excerpt(); ?>
                            </div>
                            
                            <a href="<?php the_permalink(); ?>" class="event-read-more">
                                <?php _e('View Details', 'develer-events'); ?> →
                            </a>
                        </div>
                    </article>
                <?php endwhile; ?>
            </div>
            
            <div class="events-pagination">
                <?php
                the_posts_pagination(array(
                    'mid_size' => 2,
                    'prev_text' => __('← Previous', 'develer-events'),
                    'next_text' => __('Next →', 'develer-events'),
                ));
                ?>
            </div>
        <?php else : ?>
            <div class="no-events">
                <p><?php _e('No events found. Please try different filters.', 'develer-events'); ?></p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php get_footer(); ?>

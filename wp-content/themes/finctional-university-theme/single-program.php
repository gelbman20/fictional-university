<?php
    get_header();

    $currentID = get_the_ID();
    $parentID  = wp_get_post_parent_id( $currentID );
?>

<?php while ( have_posts() ): the_post(); ?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
             style="background-image: url(<?= get_template_directory_uri() ?>/images/ocean.jpg);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?php the_title() ?></h1>
            <div class="page-banner__intro">
                <p>DONT FORGET REPLACE ME LATER</p>
            </div>
        </div>
    </div>

    <div class="container container--narrow page-section">
        <div class="metabox metabox--position-up metabox--with-home-link">
            <p>
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link('program') ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> All programs
                </a>
                <span class="metabox__main"><?php the_title() ?></span>
            </p>
        </div>

        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php
            $today = date('Ymd');
            $upcomingEvents = new WP_Query(array(
                'posts_per_page' => -1,
                'post_type' => 'event',
                'meta_key' => 'event_date',
                'orderby' => 'meta_value_num',
                'order' => 'ASC',
                'meta_query' => array(
                    array(
                        'key' => 'event_date',
                        'compare' => '>',
                        'value' => $today,
                        'type' => 'numeric'
                    ),
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                    )
                )
            )); ?>

            <?php if ($upcomingEvents->have_posts()): ?>
                <hr class="section-break">
                <h2 class="headline headline--medium">Related Events</h2>
                <?php while ($upcomingEvents->have_posts()): $upcomingEvents->the_post();
                    $eventDate = null;
                    $eventMonth = null;
                    $eventDay = null;

                    try {
                        $eventDate = new DateTime( get_field( 'event_date' ) );
                        $eventMonth = $eventDate->format('M');
                        $eventDay = $eventDate->format('d');
                    } catch ( Exception $e ) {}
                    ?>
                    <?php if ($eventDate): ?>
                        <div class="event-summary">
                            <a class="event-summary__date t-center" href="<?php the_permalink(); ?>">
                                <span class="event-summary__month"><?= $eventMonth; ?></span>
                                <span class="event-summary__day"><?= $eventDay ?></span>
                            </a>
                            <div class="event-summary__content">
                                <h5 class="event-summary__title headline headline--tiny"><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></h5>
                                <p><?= has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18) ?> <a href="<?php the_permalink(); ?>" class="nu gray">Learn more</a></p>
                            </div>
                        </div>
                    <?php endif; ?>
                <?php endwhile; ?>
            <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

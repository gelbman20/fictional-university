<?php
    get_header();
    $currentID = get_the_ID();
    $parentID  = wp_get_post_parent_id( $currentID );
?>

<?php while ( have_posts() ): the_post(); ?>
    <?php
    pageBanner(array(
        'title' => get_the_title(),
        'subtitle' => 'DONT FORGET REPLACE ME LATER'
    ));
    ?>

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
            $professors = new WP_Query(array(
                'per_page' => '1',
                'post_type' => 'professor',
                'meta_key' => 'related_programs',
                'orderby' => 'title',
                'meta_query' => array(
                    array(
                        'key' => 'related_programs',
                        'compare' => 'LIKE',
                        'value' => '"' . get_the_ID() . '"'
                        )
                    )
                )
            );
        ?>

        <?php if ($professors->have_posts()): ?>
            <hr class="section-break">
            <h2 class="headline headline--medium">Professor(s)</h2>

            <ul class="professor-cards">
                <?php while ($professors->have_posts()): $professors->the_post(); ?>
                    <li class="professor-card__list-item">
                        <a class="professor-card" href="<?php the_permalink(); ?>">
                            <img src="<?= get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape') ?>" alt="" class="professor-card__image">
                            <span class="professor-card__name"><?php the_title() ?></span>
                        </a>
                    </li>
                <?php endwhile; ?>
            </ul>
        <?php endif; ?>

        <?php wp_reset_postdata(); ?>

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
                <h2 class="headline headline--medium">Related <?= get_the_title($currentID) ?> Events</h2>
                <?php while ($upcomingEvents->have_posts()) {
                    $upcomingEvents->the_post();
                    get_template_part('template-parts/event');
                } ?>
            <?php endif; ?>

        <?php wp_reset_postdata(); ?>

    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

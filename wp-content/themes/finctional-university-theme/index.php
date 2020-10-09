<?php
    get_header();
    $currentID = get_the_ID();
    $parentID  = wp_get_post_parent_id( $currentID );
?>


<div class="page-banner">
    <div class="page-banner__bg-image"
         style="background-image: url(<?= get_template_directory_uri() ?>/images/ocean.jpg);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">Welcome to our blog!</h1>
        <div class="page-banner__intro">
            <p>Keep up with our latest news!</p>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">
    <?php while ( have_posts() ): the_post(); ?>
        <div class="post-item">
            <h2 class="headline headline--medium headline--post-title">
                <a href="<?php the_permalink(); ?>"><?php the_title() ?></a>
            </h2>
            <div class="metabox">
                <p>Posted by <?php the_author_posts_link(); ?> on <?php the_time('n.j.y'); ?> in <?= get_the_category_list(', ') ?></p>
            </div>
            <div class="generic-content">
                <?php the_excerpt(); ?>
                <p>
                    <a class="btn btn--blue" href="<?php the_permalink(); ?>">Continue reading</a>
                </p>
            </div>
        </div>
    <?php endwhile; ?>
    <?= paginate_links(); ?>
</div>

<?php get_footer(); ?>

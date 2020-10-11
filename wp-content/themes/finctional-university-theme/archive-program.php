<?php
    get_header();
    $currentID = get_the_ID();
    $parentID  = wp_get_post_parent_id( $currentID );
?>


<div class="page-banner">
    <div class="page-banner__bg-image"
         style="background-image: url(<?= get_template_directory_uri() ?>/images/ocean.jpg);"></div>
    <div class="page-banner__content container container--narrow">
        <h1 class="page-banner__title">All Programs</h1>
        <div class="page-banner__intro">
            <p>There is something for every look</p>
        </div>
    </div>
</div>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while ( have_posts() ): the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></li>
        <?php endwhile; ?>
    </ul>
    <?= paginate_links(); ?>
</div>

<?php get_footer(); ?>

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
                <a class="metabox__blog-home-link" href="<?= site_url( '/blog' ) ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Blog
                </a>
                <span class="metabox__main">Posted by <?php the_author_posts_link(); ?> on <?php the_time( 'n.j.y' ); ?> in <?= get_the_category_list( ', ' ) ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

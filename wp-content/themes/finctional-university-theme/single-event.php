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
                <a class="metabox__blog-home-link" href="<?= get_post_type_archive_link( 'event' ) ?>">
                    <i class="fa fa-home" aria-hidden="true"></i> Back to Events
                </a>
                <span class="metabox__main"><?php the_title() ?></span>
            </p>
        </div>
        <div class="generic-content">
            <?php the_content(); ?>
        </div>

        <?php $relatedPrograms = get_field( 'related_programs' ); ?>
        <?php if (!empty($relatedPrograms)): ?>
            <hr class="section-break">

            <h2 class="headline headline--medium">Related Programs</h2>
            <ul class="link-list min-list">
                <?php foreach ( $relatedPrograms as $program ):
                    $permalink = get_the_permalink($program);
                    $title = get_the_title($program);
                    ?>
                    <li>
                        <a href="<?= $permalink ?>"><?= $title ?></a>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>


    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

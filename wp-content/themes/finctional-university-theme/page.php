<?php
    get_header();

    $currentID = get_the_ID();
    $parentID = wp_get_post_parent_id($currentID);
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
        <?php

        ?>
        <?php if ($parentID): ?>
            <div class="metabox metabox--position-up metabox--with-home-link">
                <p>
                    <a class="metabox__blog-home-link" href="<?= get_permalink($parentID) ?>">
                        <i class="fa fa-home" aria-hidden="true"></i> Back to <?= get_the_title($parentID) ?>
                    </a>
                    <span class="metabox__main"><?php the_title() ?></span>
                </p>
            </div>
        <?php endif; ?>

        <?php if ($parentID || get_pages(array('child_of' => $currentID))): ?>
            <div class="page-links">
                <h2 class="page-links__title"><a href="#"><?= get_the_title($parentID) ?></a></h2>
                <ul class="min-list">
                    <?php
                        wp_list_pages(array(
                            'title_li' => null,
                            'child_of' => $parentID ? $parentID : $currentID,
                            'sort_column' => 'menu_order'
                        ));
                    ?>
                </ul>
            </div>
        <?php endif; ?>


        <div class="generic-content">
			<?php the_content(); ?>
        </div>

    </div>
<?php endwhile; ?>

<?php get_footer(); ?>

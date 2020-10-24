<?php
get_header();
$currentID = get_the_ID();
$parentID  = wp_get_post_parent_id( $currentID );
pageBanner(array(
    'title' => 'All Programs',
    'subtitle' => 'There is something for every look'
));
?>

<div class="container container--narrow page-section">
    <ul class="link-list min-list">
        <?php while ( have_posts() ): the_post(); ?>
            <li><a href="<?php the_permalink(); ?>"><?php the_title() ?></a></li>
        <?php endwhile; ?>
    </ul>
    <?= paginate_links(); ?>
</div>

<?php get_footer(); ?>

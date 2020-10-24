<?php
get_header();
$currentID = get_the_ID();
$parentID  = wp_get_post_parent_id( $currentID );

pageBanner(array(
    'title' => 'Events',
    'subtitle' => 'Read our Events'
));
?>

<div class="container container--narrow page-section">
	<?php while ( have_posts() ) {
	    the_post();
	    get_template_part('template-parts/event');
        paginate_links();
	} ?>

    <hr class="section-break">

    <p>Looking for a recap a past events? <a href="<?php site_url('past-events') ?>">Check our past events archive</a></p>
</div>

<?php get_footer(); ?>

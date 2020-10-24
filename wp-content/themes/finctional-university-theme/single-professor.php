<?php
get_header();

$currentID = get_the_ID();
$parentID = wp_get_post_parent_id($currentID);
?>

<?php while (have_posts()): the_post(); ?>
    <?php pageBanner(array(
        'title' => get_field('page_banner_subtitle'),
        'subtitle' => 'Subtitle'
    )); ?>
    <div class="container container--narrow page-section">
        <div class="generic-content">
            <div class="row group">
                <div class="one-third">
                    <?php the_post_thumbnail('professorPortrait'); ?>
                </div>
                <div class="two-thirds">
                    <?php the_content(); ?>
                </div>
            </div>
        </div>

        <?php $relatedPrograms = get_field('related_programs'); ?>
        <?php if (!empty($relatedPrograms)): ?>
            <hr class="section-break">

            <h2 class="headline headline--medium">Subject(s) Taught</h2>
            <ul class="link-list min-list">
                <?php foreach ($relatedPrograms as $program):
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

<?php

require get_theme_file_path('/inc/search-route.php');

function university_custom_rest () {
    register_rest_field('post', 'authorName', array(
        'get_callback' => function () {
            return get_the_author();
        }
    ));
}

add_action('rest_api_init', 'university_custom_rest');

function pageBanner (array $args = null) {

    if (!$args['title']) {
        $args['title'] = get_the_title();
    }

    if (!$args['background']) {
        $args['background'] = get_field('page_banner_background_image')['sizes']['pageBanner'] ?? get_template_directory_uri() . "/images/ocean.jpg";
    }

    ?>
    <div class="page-banner">
        <div class="page-banner__bg-image"
             style="background-image: url(<?= $args['background'] ?>);"></div>
        <div class="page-banner__content container container--narrow">
            <h1 class="page-banner__title"><?= $args['title'] ?></h1>
            <div class="page-banner__intro">
                <p><?= $args['subtitle'] ?></p>
            </div>
        </div>
    </div>
    <?php
}

function university_files () {
	wp_enqueue_style('google_font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
	wp_enqueue_style('font_awesome', '//maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css');
	wp_enqueue_style('university_main_styles', get_stylesheet_uri(), false, microtime());
	wp_enqueue_script('main', get_template_directory_uri() . '/js/scripts-bundled.js', array(), microtime(), true);
	wp_localize_script('main', 'universityData', array(
	    'root_url' => get_site_url()
    ));
}

add_action('wp_enqueue_scripts', 'university_files');

function university_features () {
    register_nav_menu('headerMenuLocation', 'Header Menu Location');
    register_nav_menu('footerLocationOne', 'Footer Location One');
    register_nav_menu('footerLocationTwo', 'Footer Location Two');
	add_theme_support('title-tag');
	add_theme_support('post-thumbnails');
	add_image_size('professorLandscape', 400, 260, true);
	add_image_size('professorPortrait', 480, 650, true);
	add_image_size('pageBanner', 1500, 350, true);
}

add_action('after_setup_theme', 'university_features');

function university_adjust_queries ($query) {
    if (!is_admin() && is_post_type_archive('event') && $query->is_main_query()) {
        $today = date('Ymd');
        $query->set('post_type', 'event');
        $query->set('meta_key', 'event_date');
        $query->set('orderby', 'meta_value_num');
        $query->set('order', 'ASC');
        $query->set('meta_query', array(
            array(
                'key' => 'event_date',
                'compare' => '>=',
                'value' => $today,
                'type' => 'numeric'
            )
        ));
    }

    if (!is_admin() && is_post_type_archive('program' && $query->is_main_query())) {
        $query->set('posts_per_page', -1);
        $query->set('orderby', 'title');
        $query->set('order', 'ASC');
    }
}

add_action('pre_get_posts', 'university_adjust_queries');

// Redirect
function redirectSubsToFrontend () {
    $currentUser = wp_get_current_user();

    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        wp_redirect(site_url('/'));
        exit;
    }
}

add_action('admin_init', 'redirectSubsToFrontend');

function noSubsAdminBur () {
    $currentUser = wp_get_current_user();

    if (count($currentUser->roles) === 1 && $currentUser->roles[0] === 'subscriber') {
        show_admin_bar(false);
    }
}

add_action('wp_loaded', 'noSubsAdminBur');

// Customize Login Screen
function headerUrl () {
    return esc_url(site_url('/'));
}

add_filter('login_headerurl', 'headerUrl');


function loginCSS () {
    wp_enqueue_style('google_font', '//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i|Roboto:100,300,400,400i,700,700i');
    wp_enqueue_style('university_main_styles', get_stylesheet_uri(), false, microtime());
}

add_action('login_enqueue_scripts', 'loginCSS');

function loginTitle () {
    return get_bloginfo();
}

add_filter('login_headertext', 'loginTitle');

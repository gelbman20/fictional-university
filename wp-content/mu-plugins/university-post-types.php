<?php
/*
Plugin Name: Custom Post Types
Author: Andrii Helever
Version: 1.0.0
Author URI: https://www.linkedin.com/in/andrii-helever-4aa838a1/
*/

// Custom Events Type
function university_post_types () {
	register_post_type('event', array(
		// По умолчанию slug будет event, а тут меняем на events
		// по этому слагу будет отображаться страница archive
		'rewrite' => array(
			'slug' => 'events'
		),
		// Включаем поддержку archive
		'has_archive' => true,
		'public' => true,
		// Меняем отображение в сайдбаре и в целом в админке
		'labels' => array(
			'name' => 'Events',
			'add_new_item' => 'Add New Event',
			'edit_item' => 'Edit Event',
			'all_items' => 'All Events',
			'singular_name' => 'Event'
		),
		'menu_icon' => 'dashicons-calendar',
		'supports' => array('title', 'editor', 'author', 'excerpt')
	));

	register_post_type('program', array(
	    'rewrite' => array(
	        'slug' => 'programs'
        ),
        'has_archive' => true,
        'public' => true,
        'labels' => array(
            'name' => 'Programs',
            'add_new_item' => 'Add New Program',
            'edit_item' => 'Edit Program',
            'all_items' => 'All Programs',
            'singular_name' => 'Program'
        ),
        'menu_icon' => 'dashicons-awards',
        'supports' => array('title', 'editor')
    ));

	register_post_type('professor', array(
        'public' => true,
        'labels' => array(
            'name' => 'Professor',
            'add_new_item' => 'Add New Professor',
            'edit_item' => 'Edit Professor',
            'all_items' => 'All Professor',
            'singular_name' => 'Professor'
        ),
        'menu_icon' => 'dashicons-welcome-learn-more',
        'supports' => array('title', 'editor', 'thumbnail')
    ));
}
add_action('init', 'university_post_types');

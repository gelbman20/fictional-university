<?php

function universityRegisterSearch () {
    register_rest_route('university/v1', 'search', array(
        'methods' => WP_REST_Server::READABLE,
        'callback' => 'universitySearchResults'
    ));
}

function universitySearchResults ($data) {
    $mainQuery = new WP_Query(array(
        'posts_per_page' => -1,
        'post_type' => array('page', 'post', 'professor', 'program', 'campus', 'event'),
        's'         => sanitize_text_field($data['term'])
    ));

    $result = array(
        'generalInfo' => array(),
        'professors'  => array(),
        'programs'    => array(),
        'events'      => array(),
        'campuses'    => array()
    );

    while ($mainQuery->have_posts()) {
        $mainQuery->the_post();

        $data = array(
            'id'        => get_the_ID(),
            'title'     => get_the_title(),
            'permalink' => get_the_permalink(),
            'type'      => get_post_type()
        );

        if (get_post_type() === 'page' || get_post_type() === 'post') {
            array_push($result['generalInfo'], $data);
        }

        if (get_post_type() === 'professor') {
            $data['image'] = get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape');
            array_push($result['professors'], $data);
        }

        if (get_post_type() === 'program') {
            $relatedCampus = get_field('related_campus');

            if ($relatedCampus) {
                foreach ($relatedCampus as $campus) {
                    array_push($result['campuses'], array(
                        'title'     => get_the_title($campus),
                        'permalink' => get_the_permalink($campus)
                    ));
                }
            }

            array_push($result['programs'], $data);
        }

        if (get_post_type() === 'event') {
            try {
                $eventDate = new DateTime( get_field( 'event_date' ) );
                $eventMonth = $eventDate->format('M');
                $eventDay = $eventDate->format('d');
                $data['month'] = $eventMonth;
                $data['day'] = $eventDay;
            } catch ( Exception $e ) {}

            $data['content'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);

            array_push($result['events'], $data);
        }

        if (get_post_type() === 'campus') {
            array_push($result['campuses'], $data);
        }
    }

    wp_reset_postdata();

    if ($result['professors']) {
        foreach ($result['professors'] as $professor) {
            $relatedPrograms = get_field( 'related_programs', $professor['id']);

            if ($relatedPrograms) {
                foreach ($relatedPrograms as $program) {
                    $data = array(
                        'id' => $program->ID,
                        'title' => get_the_title($program),
                        'permalink' => get_the_permalink($program),
                        'type' => $program->post_type
                    );

                    array_push($result['programs'], $data);
                }
            }
        }
    }

    if ($result['programs']) {

        $programsMetaQuery = array('relation' => 'OR');

        foreach ($result['programs'] as $program) {
            array_push($programsMetaQuery, array(
                'key' => 'related_programs',
                'compare' => 'LIKE',
                'value' => '"' . $program['id'] .'"'
            ));
        }

        $programsRelationQuery = new WP_Query(array(
                'per_page' => '-1',
                'post_type' => array('professor', 'event'),
                'meta_key' => 'related_programs',
                'orderby' => 'title',
                'meta_query' => $programsMetaQuery
            )
        );

        while ($programsRelationQuery->have_posts()) {
            $programsRelationQuery->the_post();

            $data = array(
                'id'        => get_the_ID(),
                'title'     => get_the_title(),
                'permalink' => get_the_permalink(),
                'type'      => get_post_type()
            );

            if (get_post_type() === 'professor') {
                $data['image'] = get_the_post_thumbnail_url(get_the_ID(), 'professorLandscape');
                array_push($result['professors'], $data);
            }

            if (get_post_type() === 'event') {
                try {
                    $eventDate = new DateTime( get_field( 'event_date' ) );
                    $eventMonth = $eventDate->format('M');
                    $eventDay = $eventDate->format('d');
                    $data['month'] = $eventMonth;
                    $data['day'] = $eventDay;
                } catch ( Exception $e ) {}

                $data['content'] = has_excerpt() ? get_the_excerpt() : wp_trim_words(get_the_content(), 18);

                array_push($result['events'], $data);
            }
        }

        wp_reset_postdata();
    }




    $result['professors'] = array_values(array_unique($result['professors'], SORT_REGULAR));
    $result['events'] = array_values(array_unique($result['events'], SORT_REGULAR));
    $result['programs'] = array_values(array_unique($result['programs'], SORT_REGULAR));

    return $result;
}

add_action('rest_api_init', 'universityRegisterSearch');

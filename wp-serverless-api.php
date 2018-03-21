<?php
/*
Plugin Name: WP Serverless API
Author: Daniel Olson
Author URI: https://github.com/emaildano/wp-serverless-api
Description: Save WordPress Data to JSON
*/

function export_posts_in_json (){

    $args = array(
        'post_type' => 'post',
        'post_status' => 'publish',
        'posts_per_page' => -1,
    );

    $query = new WP_Query( $args );
    $posts = array();

    while( $query->have_posts() ) : $query->the_post();

    $posts[] = array(
        'title' => get_the_title(),
        'excerpt' => get_the_excerpt(),
        'author' => get_the_author()
    );

    endwhile;

    wp_reset_query();

    $data = json_encode($posts);
    $upload_dir = wp_get_upload_dir();
    $file_name = $args['post_type'] . '.json';
    $save_path = $upload_dir['basedir'] . '/wp-sls-api/' . $file_name;
    $dirname = dirname($save_path);
    if (!is_dir($dirname)) {
        mkdir($dirname, 0755, true);
    }

    $f = fopen( $save_path , "w+" );
    fwrite($f , $data);
    fclose($f);

}

add_action( 'save_post', 'export_posts_in_json' );
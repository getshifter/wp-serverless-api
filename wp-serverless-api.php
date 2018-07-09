<?php

/*
Plugin Name: WP Serverless API
Author: Daniel Olson
Author URI: https://github.com/emaildano/wp-serverless-api
Description: WordPress REST API to JSON File
*/


function export_posts_in_json() {

    $resource = array(
        'posts',
        'pages',
        'media'
    );

    foreach ($resource as $route) {
        $url =  esc_url( home_url( '/' ) ) . 'wp-json/wp/v2/' . $route . '?_embed&per_page=100';
        
        $jsonData = json_decode( file_get_contents($url) );
        $jsonEncode = json_encode($jsonData);

        $base_dir = content_url();
        $file_name = $route . '.json';
        $save_path = $base_dir . '/wp-sls-api/' . $file_name;
        $dirname = dirname($save_path);
        
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        $f = fopen( $save_path , "w+" );
        fwrite($f , $jsonEncode);
        fclose($f);
    }

}

add_action( 'save_post', 'export_posts_in_json' );
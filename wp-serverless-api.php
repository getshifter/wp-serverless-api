<?php

/*
Plugin Name: WP Serverless API
Author: Daniel Olson
Author URI: https://github.com/emaildano/wp-serverless-api
Description: WordPress REST API to JSON File
*/


function compile_db(
    $routes = array(
        'posts',
        'pages',
        'media'
    )
) {

    $db_array = array();

    foreach ($routes as $route) {
        $url =  'https://demo.wp-api.org/wp-json/wp/v2/' . $route;
        // $url =  esc_url( home_url( '/' ) ) . 'wp-json/wp/v2/' . $route . '?_embed&per_page=5';
        $jsonData = json_decode( file_get_contents($url) );

        $db_array[$route] = (array) $jsonData;
    }

    $db = json_encode($db_array);

    return $db;

}

function save_db(
        $db,
        $file_name = 'db.json'
    ) {
    $upload_dir = wp_get_upload_dir();
    $save_path = $upload_dir['basedir'] . '/wp-sls-api/' . $file_name;
    $dirname = dirname($save_path);
    
    if (!is_dir($dirname))
    {
        mkdir($dirname, 0755, true);
    }

    $f = fopen( $save_path , "w+" );
    fwrite($f , $db);
    fclose($f);
}

function build_db()
{
    $db = compile_db();
    // $db_clean = preg_match('demo.wp-api.org', 'pizza', $db);
    save_db($db);
}

add_action( 'save_post', 'build_db' );
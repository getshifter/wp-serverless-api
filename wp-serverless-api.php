<?php

/*
Plugin Name: WP Serverless API
Plugin URI: https://github.com/getshifter/wp-serverless-api
Description: WordPress REST API to JSON File
Version: 0.2.0
Author: Shifter
Author URI: https://getshifter.io
*/

function enable_permalinks_notice() {
    ?>
    <div class="notice notice-warning">
        <p><?php _e( 'WP Serverless Redirects requires Permalinks. <a href="/wp-admin/options-permalink.php">Enable Permalinks</a>'); ?></p>
    </div>
    <?php
}

if ( !get_option('permalink_structure') ) {
    add_action( 'admin_notices', 'enable_permalinks_notice' );
}

function compile_db(
    $routes = array(
        'posts',
        'pages',
        'media'
    )
) {

    $db_array = array();

    foreach ($routes as $route) {
        if  (getenv("SHIFTER_ACCESS_TOKEN") === false) {
            $url =  'https://demo.wp-api.org/wp-json/wp/v2/' . $route;
        } else {
            $url =  esc_url( home_url( '/' ) ) . 'wp-json/wp/v2/' . $route;
        }

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
    $save_path = WP_CONTENT_DIR . '/wp-sls-api/' . $file_name;
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
    save_db($db);
}

/**
 * Build on Post Save
 */
add_action( 'save_post', 'build_db' );

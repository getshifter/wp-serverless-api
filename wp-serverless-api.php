<?php

/*
Plugin Name: WP Serverless API
Plugin URI: https://github.com/getshifter/wp-serverless-api
Description: WordPress REST API to JSON File
Version: 0.2.0
Author: Shifter
Author URI: https://getshifter.io
*/

function enable_permalinks_notice()
{
?>
    <div class="notice notice-warning">
        <p><?php _e('WP Serverless Redirects requires Permalinks. <a href="/wp-admin/options-permalink.php">Enable Permalinks</a>'); ?></p>
    </div>
<?php
}

if (!get_option('permalink_structure')) {
    add_action('admin_notices', 'enable_permalinks_notice');
}

function compile_db($route)
{

    $db_array = array();

    $url =  esc_url(home_url('/')) . 'wp-json/wp/v2/' . $route;

    $arrContextOptions = array(
        "ssl" => array(
            "verify_peer" => false,
            "verify_peer_name" => false,
        ),
    );

    $response = file_get_contents($url, false, stream_context_create($arrContextOptions));

    $jsonData = json_decode($response);

    $db_array = $jsonData;

    $db = json_encode($db_array);

    return $db;
}

function save_db(
    $db,
    $file_name = 'posts'
) {
    $save_path = WP_CONTENT_DIR . '/uploads/wp-json/wp/v2/' . $file_name . '.json';
    $dirname = dirname($save_path);

    if (!is_dir($dirname)) {
        mkdir($dirname, 0755, true);
    }

    $f = fopen($save_path, "w+");
    fwrite($f, $db);
    fclose($f);
}

function build_db($post_id)
{
    $db = compile_db('posts/' . $post_id);
    save_db($db, $post_id);
}

/**
 * Build on Post Save
 */
add_action('save_post', 'build_db');

<?php

/*
Plugin Name: WP Serverless API
Plugin URI: https://github.com/getshifter/wp-serverless-api
Description: WordPress REST API to JSON File
Version: 0.2.0
Author: Shifter
Author URI: https://getshifter.io
*/

function wp_sls_api(array $result, \WP_REST_Server $server, \WP_Rest_Request $request): array
{

    $route = $request->get_route();
    $request = new WP_REST_Request('GET', $request->get_route());
    $response = rest_do_request($request);
    $server = rest_get_server();
    $data = (array) $server->response_to_data($response, false);

    // Check for routing errors.
    if ($data['data']['status'] === 404) {
        return $result;
    }

    $save_path = WP_CONTENT_DIR . '/uploads/wp-json' . $route . '.json';
    $dirname = dirname($save_path);

    if (!is_dir($dirname)) {
        mkdir($dirname, 0755, true);
    }

    $f = fopen($save_path, "w+");
    fwrite($f, json_encode($data));
    fclose($f);

    // return (array) $request->get_params();
    // return (array) $request->get_route();
    return $result;
}

add_filter('rest_pre_echo_response', 'wp_sls_api', 10, 3);

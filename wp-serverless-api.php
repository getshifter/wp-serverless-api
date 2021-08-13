<?php

/*
Plugin Name: WP Serverless API
Plugin URI: https://github.com/getshifter/wp-serverless-api
Description: WordPress REST API to JSON File
Version: 0.3.0
Author: Shifter
Author URI: https://getshifter.io
*/

function api_request(array $result, \WP_REST_Server $server, \WP_Rest_Request $request): array
{

    global $post;
    if (in_array($post['post_type'], ['post', 'page'])) {
        error_log(print_r((string) $post, true));

        $route = $request->get_route() ? $request->get_route() : 'index';
        $request = new WP_REST_Request('GET', $route);
        $response = rest_do_request($request);
        $server = rest_get_server();
        $data = (array) $server->response_to_data($response, false);
        $save_path = WP_CONTENT_DIR . '/uploads/wp-json/' . $route . '.json';
        $f = fopen($save_path, "w+");
        $dirname = dirname($save_path);

        // Check and make file directory.
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        fwrite($f, json_encode($data));
        fclose($f);

        // error_log(print_r((string) $route, true));

        return $result;
    }
}

add_filter('rest_pre_echo_response', 'api_request', 10, 3);


function user_request($post_id)
{

    $post = get_post($post_id);

    if (wp_is_post_revision($post_id)) {
        return;
    }

    if (wp_is_post_autosave($post_id)) {
        return;
    }

    if (in_array($post->post_type, ['post', 'page'])) {

        $result = [];
        $post_type = get_post_type($post_id);
        $post_type_obj = get_post_type_object($post_type);
        $post_type_name = (string) strtolower($post_type_obj->labels->name);

        $rest_path = '/wp/v2/' .  $post_type_name . '/' . $post_id;

        $route = $rest_path;
        $request = new WP_REST_Request('GET', $route);

        $response = rest_do_request($request);
        $server = rest_get_server();
        $data = (array) $server->response_to_data($response, false);
        $save_path = WP_CONTENT_DIR . '/uploads/wp-json/' . $route . '.json';
        $f = fopen($save_path, "w+");
        $dirname = dirname($save_path);

        // Check and make file directory.
        if (!is_dir($dirname)) {
            mkdir($dirname, 0755, true);
        }

        fwrite($f, json_encode($data));
        fclose($f);

        // error_log(print_r($request, true));

        return $result;
    }
}

add_action('save_post', 'user_request');

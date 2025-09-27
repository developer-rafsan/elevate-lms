<?php
defined('ABSPATH') or die('No script kiddies please!');

function elevate_lms_create_zoom_meeting($topic, $start_time) {
    $account_id = get_option('elevate_lms_zoom_account_id');
    $client_id = get_option('elevate_lms_zoom_client_id');
    $client_secret = get_option('elevate_lms_zoom_client_secret');

    if (empty($account_id) || empty($client_id) || empty($client_secret)) {
        return new WP_Error('missing_credentials', __('Zoom API credentials are not configured.', 'pixelcode'));
    }

    // Get Access Token
    $token_url = 'https://zoom.us/oauth/token';
    $token_args = [
        'headers' => [
            'Authorization' => 'Basic ' . base64_encode($client_id . ':' . $client_secret),
        ],
        'body' => [
            'grant_type' => 'account_credentials',
            'account_id' => $account_id,
        ],
    ];

    $token_response = wp_remote_post($token_url, $token_args);

    if (is_wp_error($token_response)) {
        return $token_response;
    }

    $token_body = json_decode(wp_remote_retrieve_body($token_response), true);
    if (empty($token_body['access_token'])) {
        return new WP_Error('token_error', __('Could not retrieve Zoom access token.', 'pixelcode'));
    }

    $access_token = $token_body['access_token'];

    // Create Meeting
    $meeting_url = 'https://api.zoom.us/v2/users/me/meetings';
    $meeting_args = [
        'headers' => [
            'Authorization' => 'Bearer ' . $access_token,
            'Content-Type'  => 'application/json',
        ],
        'body' => json_encode([
            'topic'      => $topic,
            'type'       => 2, // Scheduled meeting
            'start_time' => gmdate('Y-m-d\TH:i:s\Z', strtotime($start_time)),
            'settings'   => [
                'join_before_host'  => true,
                'waiting_room'      => false,
            ],
        ]),
    ];

    $meeting_response = wp_remote_post($meeting_url, $meeting_args);

    if (is_wp_error($meeting_response)) {
        return $meeting_response;
    }

    $meeting_body = json_decode(wp_remote_retrieve_body($meeting_response), true);

    if (!empty($meeting_body['join_url'])) {
        return $meeting_body['join_url'];
    } else {
        return new WP_Error('meeting_error', __('Could not create Zoom meeting.', 'pixelcode'), $meeting_body);
    }
}

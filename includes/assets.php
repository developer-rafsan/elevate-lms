<?php
// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

/**
 * Enqueue admin scripts and styles for Elevate LMS pages
 */
function elevate_lms_admin_assets( $hook ) {
    $allowed_hooks = [
        'toplevel_page_elevate-lms-dashboard',
        'elevate-lms_page_elevate-lms-classes',
        'elevate-lms-dashboard_page_elevate-lms-classes',
        'elevate-lms_page_elevate-lms-add-class',
        'elevate-lms_page_elevate-lms-subscription',
        'elevate-lms_page_elevate-lms-students',
        'elevate-lms_page_elevate-lms-tutors',
        'elevate-lms_page_elevate-lms-categories',
    ];

    if ( ! in_array( $hook, $allowed_hooks, true ) ) {
        return;
    }

    // Load admin CSS
    wp_enqueue_style(
        'elevate-lms-admin-css',
        plugin_dir_url( __FILE__ ) . '../assets/css/admin.css',
        [],
        '1.0.0',
        'all'
    );

    // Font Awesome CDN
    wp_enqueue_style(
        'font-awesome-cdn',
        'https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css',
        [],
        '6.4.0'
    );

    // Load admin JS
    wp_enqueue_script(
        'elevate-lms-admin-js',
        plugin_dir_url( __FILE__ ) . '../assets/js/admin.js',
        [ 'jquery' ],
        '1.0.0',
        true
    );
}
add_action( 'admin_enqueue_scripts', 'elevate_lms_admin_assets' );


/**
 * Enqueue frontend scripts and styles securely
 */
function elevate_lms_frontend_assets() {
    // Enqueue frontend CSS
    wp_enqueue_style(
        'elevate-lms-frontend-css',
        plugin_dir_url( __FILE__ ) . '../assets/css/style.css',
        [],
        '1.0.0',
        'all'
    );

    // Enqueue frontend JS
    wp_enqueue_script(
        'elevate-lms-frontend-js',
        plugin_dir_url( __FILE__ ) . '../assets/js/main.js',
        ['jquery'],
        '1.0.0',
        true
    );
}
add_action( 'wp_enqueue_scripts', 'elevate_lms_frontend_assets' );
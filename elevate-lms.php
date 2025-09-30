<?php
/**
 * Plugin Name: Elevate LMS
 * Plugin URI:  
 * Description: A lightweight, customizable Learning Management System (LMS) plugin to create and manage courses, lessons, and quizzes seamlessly within WordPress.
 * Version:     1.0.0
 * Author:      PIXELCODE
 * Author URI:  https://portfolio-client-y9gw.onrender.com/
 * License:     GPL-2.0-or-later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: pixelcode
 */

// Prevent direct access
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

// Define plugin directory path
define( 'ELEVATE_LMS_DIR', plugin_dir_path( __FILE__ ) );


// Include necessary files
require_once __DIR__ . '/includes/assets.php';
require_once __DIR__ . '/admin/menu.php';


// Register plugin activation and deactivation hooks
register_activation_hook( __FILE__, 'elevate_lms_activate' );

// Plugin activation function
function elevate_lms_activate() {
    // Include the database setup file
    $db_file = plugin_dir_path( __FILE__ ) . 'includes/database.php';

    if ( file_exists( $db_file ) ) {
        require_once $db_file;
    } else {
        wp_die( esc_html__( 'Database setup file not found.', 'pixelcode' ) );
    }
}

// Hook to handle form submission
add_action('admin_post_add_class_action', 'elevate_lms_handle_add_class');

function elevate_lms_handle_add_class() {
    // Include the form handling file
    $action_file = plugin_dir_path( __FILE__ ) . 'admin/action/add-class.php';

    if ( file_exists( $action_file ) ) {
        require_once $action_file;
    } else {
        wp_die( esc_html__( 'Action file not found.', 'pixelcode' ) );
    }
}

// Register settings
add_action('admin_init', 'elevate_lms_register_settings');
function elevate_lms_register_settings() {
    register_setting('elevate_lms_settings_group', 'elevate_lms_zoom_account_id');
    register_setting('elevate_lms_settings_group', 'elevate_lms_zoom_client_id');
    register_setting('elevate_lms_settings_group', 'elevate_lms_zoom_client_secret');

    add_settings_section('elevate_lms_zoom_settings_section', 'Zoom API Settings', null, 'elevate-lms-settings');

    add_settings_field('elevate_lms_zoom_account_id', 'Zoom Account ID', 'elevate_lms_zoom_account_id_callback', 'elevate-lms-settings', 'elevate_lms_zoom_settings_section');
    add_settings_field('elevate_lms_zoom_client_id', 'Zoom Client ID', 'elevate_lms_zoom_client_id_callback', 'elevate-lms-settings', 'elevate_lms_zoom_settings_section');
    add_settings_field('elevate_lms_zoom_client_secret', 'Zoom Client Secret', 'elevate_lms_zoom_client_secret_callback', 'elevate-lms-settings', 'elevate_lms_zoom_settings_section');
}

function elevate_lms_zoom_account_id_callback() {
    $option = get_option('elevate_lms_zoom_account_id');
    echo '<input type="text" id="elevate_lms_zoom_account_id" name="elevate_lms_zoom_account_id" value="' . esc_attr($option) . '" />';
}

function elevate_lms_zoom_client_id_callback() {
    $option = get_option('elevate_lms_zoom_client_id');
    echo '<input type="text" id="elevate_lms_zoom_client_id" name="elevate_lms_zoom_client_id" value="' . esc_attr($option) . '" />';
}

function elevate_lms_zoom_client_secret_callback() {
    $option = get_option('elevate_lms_zoom_client_secret');
    echo '<input type="password" id="elevate_lms_zoom_client_secret" name="elevate_lms_zoom_client_secret" value="' . esc_attr($option) . '" />';
}

add_action('admin_init', 'elevate_lms_prefill_zoom_credentials');
function elevate_lms_prefill_zoom_credentials() {
    if (get_option('elevate_lms_zoom_account_id') === false) {
        update_option('elevate_lms_zoom_account_id', 's2Wt4KvJRNCDrYNlM5KKXg');
    }
    if (get_option('elevate_lms_zoom_client_id') === false) {
        update_option('elevate_lms_zoom_client_id', 'p6jpldfR92Nut7R3Hejg');
    }
    if (get_option('elevate_lms_zoom_client_secret') === false) {
        update_option('elevate_lms_zoom_client_secret', 'mnf3dBh1PmE1bTeCvDuBeTF4bnEGV7Qg');
    }
}

// the_embed_site_title(  )
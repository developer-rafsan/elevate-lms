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
require_once ELEVATE_LMS_DIR . 'admin/menu.php';
require_once ELEVATE_LMS_DIR . 'includes/assets.php';

// Register plugin activation and deactivation hooks
register_activation_hook( __FILE__, 'elevate_lms_activate' );

// Plugin activation function
function elevate_lms_activate() {
    
    // Include the database setup file
    $db_file = plugin_dir_path( __FILE__ ) . 'includes/db.php';

    if ( file_exists( $db_file ) ) {
        require_once $db_file;
    } else {
        wp_die( esc_html__( 'Database setup file not found.', 'pixelcode' ) );
    }
}

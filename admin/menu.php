<?php

// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Add the main "Elevate LMS" menu and sub-menu items
function elevate_lms_dashboard_menu() {
    // Main menu
    add_menu_page(
        'Elevate LMS',                
        'Elevate LMS',                
        'manage_options',            
        'elevate-lms-dashboard',     
        'elevate_lms_dashboard_page',
        'dashicons-book',            
        6                            
    );

    // Sub-menu: Dashboard
    add_submenu_page(
        'elevate-lms-dashboard',
        'Dashboard',
        'Dashboard',
        'manage_options',
        'elevate-lms-dashboard',
        'elevate_lms_dashboard_page'
    );

    // Sub-menu: All Classes
    add_submenu_page(
        'elevate-lms-dashboard',
        'All Classes',
        'All Classes',
        'manage_options',
        'elevate-lms-classes',
        'elevate_lms_classes_page'
    );

    // Sub-menu: Add New Class
    add_submenu_page(
        'elevate-lms-dashboard',
        'Add New Class',
        'Add New Class',
        'manage_options',
        'elevate-lms-add-class',
        'elevate_lms_add_class_page'
    );

    // Sub-menu: Create Subscription
    add_submenu_page(
        'elevate-lms-dashboard',
        'Create Subscription',
        'Create Subscription',
        'manage_options',
        'elevate-lms-subscription',
        'elevate_lms_subscription_page'
    );

    // Sub-menu: Students
    add_submenu_page(
        'elevate-lms-dashboard',
        'Students',
        'Students',
        'manage_options',
        'elevate-lms-students',
        'elevate_lms_students_page'
    );

    // Sub-menu: Tutors
    add_submenu_page(
        'elevate-lms-dashboard',
        'Tutors',
        'Tutors',
        'manage_options',
        'elevate-lms-tutors',
        'elevate_lms_tutors_page'
    );
}
add_action( 'admin_menu', 'elevate_lms_dashboard_menu' );


// Template loader function (reusable)
function elevate_lms_include_template( $template_file, $fallback_title ) {
    if ( ! current_user_can( 'manage_options' ) ) {
        wp_die( esc_html__( 'You do not have sufficient permissions to access this page.', 'pixelcode' ) );
    }

    // Correct absolute path
    $file = plugin_dir_path( __FILE__ ) . $template_file;

    if ( file_exists( $file ) ) {
        include $file;
    } else {
        echo '<div class="wrap">';
        echo '<h1>' . esc_html__( $fallback_title, 'pixelcode' ) . '</h1>';
        echo '<p>' . esc_html__( 'Template file not found: ' . esc_html($file), 'pixelcode' ) . '</p>';
        echo '</div>';
    }
}


// Callback functions
function elevate_lms_dashboard_page() {
    elevate_lms_include_template( 'dashboard.php', 'Elevate LMS Dashboard' );
}

function elevate_lms_classes_page() {
    elevate_lms_include_template( 'classes.php', 'All Classes' );
}

function elevate_lms_add_class_page() {
    elevate_lms_include_template( 'add-class.php', 'Add New Class' );
}

function elevate_lms_subscription_page() {
    elevate_lms_include_template( 'subscription.php', 'Create Subscription' );
}

function elevate_lms_students_page() {
    elevate_lms_include_template( 'students.php', 'Students' );
}

function elevate_lms_tutors_page() {
    elevate_lms_include_template( 'tutors.php', 'Tutors' );
}

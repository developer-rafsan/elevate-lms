<?php
defined('ABSPATH') or die('No script kiddies please!');

require_once ELEVATE_LMS_DIR . 'includes/zoom-api.php';

global $wpdb;
$wpdb->show_errors();

$classes_table = $wpdb->prefix . 'elevate_lms_classes';

if (isset($_POST['add_class_nonce']) && wp_verify_nonce($_POST['add_class_nonce'], 'add_class_action')) {

    // Sanitize and validate input
    $title = sanitize_text_field($_POST['course_title']);
    $description = wp_kses_post($_POST['course_description']);
    $difficulty_level = sanitize_text_field($_POST['difficulty']);
    if ($difficulty_level === 'custom') {
        $difficulty_level = sanitize_text_field($_POST['custom_difficulty']);
    }
    $duration = sanitize_text_field($_POST['duration']);
    $start_date = sanitize_text_field($_POST['start_date']);
    $start_time = sanitize_text_field($_POST['start_time']);
    $start_date_time = $start_date && $start_time ? date('Y-m-d H:i:s', strtotime("$start_date $start_time")) : null;

    $end_date = sanitize_text_field($_POST['end_date']);
    $end_time = sanitize_text_field($_POST['end_time']);
    $end_date_time = $end_date && $end_time ? date('Y-m-d H:i:s', strtotime("$end_date $end_time")) : null;

    $course_type = sanitize_text_field($_POST['pricing']);
    $subscription_id = ($course_type === 'paid' && isset($_POST['subscription_id'])) ? intval($_POST['subscription_id']) : null;

    $class_type = sanitize_text_field($_POST['class_type']);
    $pre_recorded_video = ($class_type === 'pre-recorded' && isset($_POST['pre_recorded_video'])) ? esc_url_raw($_POST['pre_recorded_video']) : null;
    
    $zoom_link = null;
    if ($class_type === 'live') {
        $zoom_link = elevate_lms_create_zoom_meeting($title, $start_date_time);
        if (is_wp_error($zoom_link)) {
            // Handle error: for now, we'll just log it and redirect with an error message.
            error_log($zoom_link->get_error_message());
            wp_redirect(admin_url('admin.php?page=elevate-lms-add-class&message=zoom_error'));
            exit;
        }
    }

    $category_id = intval($_POST['categories']);
    $tutor_id = intval($_POST['tutor_id']);
    $featured_image = intval($_POST['featured_image']);
    $intro_video = esc_url_raw($_POST['intro_video']);
    $requirements = sanitize_textarea_field($_POST['requrment']);

    // Insert data into the database
    $result = $wpdb->insert(
        $classes_table,
        array(
            'title' => $title,
            'description' => $description,
            'difficulty_level' => $difficulty_level,
            'duration' => $duration,
            'start_date_time' => $start_date_time,
            'end_date_time' => $end_date_time,
            'course_type' => $course_type,
            'subscription_id' => $subscription_id,
            'class_type' => $class_type,
            'pre_recorded_video' => $pre_recorded_video,
            'zoom_link' => $zoom_link,
            'category_id' => $category_id,
            'tutor_id' => $tutor_id,
            'featured_image' => $featured_image,
            'intro_video' => $intro_video,
            'requirements' => $requirements,
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%s', '%d', '%s', '%s', '%s', '%d', '%d', '%d', '%s', '%s')
    );

    if ($result === false) {
        echo "Database Insert Failed: ";
        $wpdb->print_error();
        die();
    }

    // Redirect after insertion
    wp_redirect(admin_url('admin.php?page=elevate-lms-classes&message=success'));
    exit;
}
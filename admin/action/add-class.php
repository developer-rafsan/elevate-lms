<?php
defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;
$classes_table = $wpdb->prefix . 'elevate_lms_classes';

if (isset($_POST['add_class_nonce']) && wp_verify_nonce($_POST['add_class_nonce'], 'add_class_action')) {

    // Sanitize and validate input
    $title = sanitize_text_field($_POST['course_title']);
    $description = wp_kses_post($_POST['course_description']);
    $difficulty_level = sanitize_text_field($_POST['difficulty']);
    if ($difficulty_level === 'custom') {
        $difficulty_level = sanitize_text_field($_POST['custom_difficulty']);
    }
    $start_date = sanitize_text_field($_POST['start_date']);
    $start_time = sanitize_text_field($_POST['start_time']);
    $start_date_time = $start_date && $start_time ? date('Y-m-d H:i:s', strtotime("$start_date $start_time")) : null;

    $end_date = sanitize_text_field($_POST['end_date']);
    $end_time = sanitize_text_field($_POST['end_time']);
    $end_date_time = $end_date && $end_time ? date('Y-m-d H:i:s', strtotime("$end_date $end_time")) : null;

    $course_type = sanitize_text_field($_POST['pricing']);
    $subscription_id = ($course_type === 'paid' && isset($_POST['subscription_id'])) ? intval($_POST['subscription_id']) : null;

    $category_id = intval($_POST['categories']);
    $tutor_id = intval($_POST['tutor_id']);
    $featured_image = intval($_POST['featured_image']);
    $intro_video = esc_url_raw($_POST['intro_video']);
    $requirements = sanitize_textarea_field($_POST['requrment']);

    // Insert data into the database
    $wpdb->insert(
        $classes_table,
        array(
            'title' => $title,
            'description' => $description,
            'difficulty_level' => $difficulty_level,
            'start_date_time' => $start_date_time,
            'end_date_time' => $end_date_time,
            'course_type' => $course_type,
            'subscription_id' => $subscription_id,
            'category_id' => $category_id,
            'tutor_id' => $tutor_id,
            'featured_image' => $featured_image,
            'intro_video' => $intro_video,
            'requirements' => $requirements,
        ),
        array('%s', '%s', '%s', '%s', '%s', '%s', '%d', '%d', '%d', '%d', '%s', '%s')
    );

    // Redirect after insertion
    wp_redirect(admin_url('admin.php?page=elevate-lms-classes&message=success'));
    exit;
}
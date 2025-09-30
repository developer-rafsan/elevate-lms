<?php
// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$charset_collate = $wpdb->get_charset_collate();

// Subscriptions table
$subscriptions_table = $wpdb->prefix . 'elevate_lms_subscriptions';

$subscriptions_sql = "CREATE TABLE {$subscriptions_table} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    duration varchar(50) NOT NULL,
    offer_price decimal(10,2) NOT NULL,
    regular_price decimal(10,2) NOT NULL,
    features longtext DEFAULT NULL,
    created_at datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY  (id)
) {$charset_collate};";

// Categories table
$categories_table = $wpdb->prefix . 'elevate_lms_categories';

$categories_sql = "CREATE TABLE {$categories_table} (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    category_name varchar(255) NOT NULL,
    deleted tinyint(1) NOT NULL DEFAULT 0,
    PRIMARY KEY  (id)
) {$charset_collate};";

// Classes table
$classes_table = $wpdb->prefix . 'elevate_lms_classes';

$classes_sql = "CREATE TABLE {$classes_table} (
    id BIGINT(20) UNSIGNED NOT NULL AUTO_INCREMENT,
    title VARCHAR(255) NOT NULL,
    description LONGTEXT,
    difficulty_level VARCHAR(50),
    duration VARCHAR(50),
    start_date_time DATETIME,
    end_date_time DATETIME,
    course_type VARCHAR(20) NOT NULL DEFAULT 'free',
    class_type VARCHAR(20) NOT NULL DEFAULT 'pre-recorded',
    pre_recorded_video VARCHAR(255),
    zoom_link VARCHAR(255),
    subscription_id MEDIUMINT(9),
    category_id MEDIUMINT(9),
    featured_image BIGINT(20) UNSIGNED,
    intro_video VARCHAR(255),
    requirements LONGTEXT,
    tutor_id BIGINT(20) UNSIGNED,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    FOREIGN KEY (category_id) REFERENCES {$wpdb->prefix}elevate_lms_categories(id) ON DELETE SET NULL,
    FOREIGN KEY (subscription_id) REFERENCES {$wpdb->prefix}elevate_lms_subscriptions(id) ON DELETE SET NULL,
    FOREIGN KEY (tutor_id) REFERENCES {$wpdb->prefix}users(ID) ON DELETE SET NULL
) {$charset_collate};";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $subscriptions_sql );
dbDelta( $categories_sql );
dbDelta( $classes_sql );

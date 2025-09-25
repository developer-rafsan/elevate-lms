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

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $subscriptions_sql );
dbDelta( $categories_sql );

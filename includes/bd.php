<?php
// Prevent direct access
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';
$charset_collate = $wpdb->get_charset_collate();

$sql = "CREATE TABLE $table_name (
    id mediumint(9) NOT NULL AUTO_INCREMENT,
    title varchar(255) NOT NULL,
    price decimal(10,2) NOT NULL,
    duration varchar(50) NOT NULL,
    per_class_price decimal(10,2) NOT NULL,
    features text NOT NULL,
    created_at datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
    PRIMARY KEY  (id)
) $charset_collate;";

require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
dbDelta( $sql );
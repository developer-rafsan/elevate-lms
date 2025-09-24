<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';

if ( isset( $_GET['id'] ) ) {
    $id = intval( $_GET['id'] );
    $wpdb->delete( $table_name, array( 'id' => $id ) );
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Subscription deleted successfully!</p>
    </div>
    <a href="?page=elevate-lms-subscriptions">Back to subscriptions</a>
    <?php
} else {
    ?>
    <div class="notice notice-error is-dismissible">
        <p>No subscription ID found to delete.</p>
    </div>
    <a href="?page=elevate-lms-subscriptions">Back to subscriptions</a>
    <?php
}

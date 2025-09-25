<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';

// Handle form submission
if( isset($_POST['add_subscription']) ) {
    $title = sanitize_text_field($_POST['title']);
    $duration = sanitize_text_field($_POST['duration']);
    $offer_price = floatval($_POST['offer_price']);
    $regular_price = floatval($_POST['regular_price']);
    
    $features = isset($_POST['features']) ? $_POST['features'] : [];
    $features_json = wp_json_encode($features);

    $wpdb->insert(
        $table_name,
        [
            'title' => $title,
            'duration' => $duration,
            'offer_price' => $offer_price,
            'regular_price' => $regular_price,
            'features' => $features_json,
        ],
        [
            '%s','%s','%f','%f','%s'
        ]
    );
    wp_redirect( admin_url( 'admin.php?page=elevate-lms-subscription' ) );
    exit;
}?>

<div class="wrap add-subscription-form">
    <div class='card'>
        <div class="card-header flex justify-between items-center mb-4">
            <h5>Add New Subscription</h5>
            <button type="button" class="btn-close" aria-label="Close" onclick="document.querySelector('.add-subscription-form').classList.remove('active');">
                <i class="fa-solid fa-xmark"></i>
            </button>
        </div>
        <div class="card-body">
            <form method="post" action="">
                <div class="mb-3">
                    <label for="title" class="form-label">Title</label>
                    <input name="title" type="text" id="title" class="form-control" required>
                </div>

                <div class="mb-3">
                    <label for="duration" class="form-label">Duration</label>
                    <select name="duration" id="duration" class="form-control" required>
                        <option value="">Select Duration</option>
                        <option value="monthly">Monthly</option>
                        <option value="yearly">Yearly</option>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="offer_price" class="form-label">Offer Price</label>
                    <input name="offer_price" type="number" id="offer_price" class="form-control" step="0.01" required>
                </div>

                <div class="mb-3">
                    <label for="regular_price" class="form-label">Regular Price</label>
                    <input name="regular_price" type="number" id="regular_price" class="form-control" step="0.01" required>
                </div>

                <div id="features-wrapper" class="mb-3">
                    <div class="feature-item mb-2">
                        <label for="features-1" class="form-label">Features</label>
                        <input name="features[]" id="features-1" class="form-control">
                    </div>
                </div>

                <button type="button" id="add-feature" class="btn btn-secondary mb-3">Add more features</button>

                <div>
                    <button type="submit" name="add_subscription" class="btn btn-primary">
                        Add Subscription
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

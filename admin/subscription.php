<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Include the Add Subscription form
include plugin_dir_path(__FILE__) . 'action/add-subscription.php';

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';

// Fetch all subscriptions
$subscriptions = $wpdb->get_results("SELECT * FROM $table_name ORDER BY created_at DESC");
?>

<div class="subscription">
    <div class="header flex items-center justify-between mb-4">
        <h1 class="text-3xl font-bold text-gray-800">Subscription</h1>
        <button class="page-title-action" onclick="document.querySelector('.add-subscription-form').classList.add('active');">
            Add Subscription
        </button>
    </div>

    <div class="filter mb-4 space-x-2">
        <button class="active px-3 py-1 border rounded">All (<?php echo count($subscriptions); ?>)</button>
        <button class="px-3 py-1 border rounded">Published (5)</button>
        <button class="px-3 py-1 border rounded">Trash (1)</button>
    </div>

    <div class="action mb-4 flex items-center space-x-2">
        <select name="actions" id="actions" class="border rounded px-2 py-1">
            <option>Bulk actions</option>
            <option value="edit">Edit</option>
            <option value="trash">Move to Trash</option>
        </select>
        <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">
            Apply
        </button>
    </div>

    <table class="pricing-table w-full border-collapse" role="table" aria-label="Pricing table">
        <thead>
            <tr class="bg-gray-100 text-left">
                <th class="p-2 border">Title</th>
                <th class="p-2 border">Duration</th>
                <th class="p-2 border">Offer Price</th>
                <th class="p-2 border">Regular Price</th>
                <th class="p-2 border">Features</th>
                <th class="p-2 border">Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if($subscriptions): ?>
                <?php foreach($subscriptions as $sub): 
                    $features = json_decode($sub->features, true); ?>
                    <tr>
                        <td data-label="Title" class="p-2 border"><?php echo esc_html($sub->title); ?></td>
                        <td data-label="Duration" class="p-2 border"><?php echo esc_html(ucfirst($sub->duration)); ?></td>
                        <td data-label="Offer Price" class="p-2 border text-red-600 font-bold">৳<?php echo number_format($sub->offer_price, 2); ?></td>
                        <td data-label="Regular Price" class="p-2 border line-through text-gray-500">৳<?php echo number_format($sub->regular_price, 2); ?></td>
                        <td data-label="Features" class="p-2 border">
                            <?php if($features && is_array($features)): ?>
                                <ul>
                                <?php foreach($features as $feature): ?>
                                    <li><?php echo esc_html($feature); ?></li>
                                <?php endforeach; ?>
                                </ul>
                            <?php endif; ?>
                        </td>
                        <td data-label="Action" class="p-2 border space-x-2">
                            <a class="bg-blue-600 text-white px-2 py-1 rounded hover:bg-blue-700"
                               href="?page=elevate-lms-edit-subscription&id=<?php echo $sub->id; ?>">Edit</a>
                            <a class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700"
                               href="?page=elevate-lms-delete-subscription&id=<?php echo $sub->id; ?>">Delete</a>
                            <a class="bg-gray-600 text-white px-2 py-1 rounded hover:bg-gray-700"
                               href="?page=elevate-lms-view-subscription&id=<?php echo $sub->id; ?>">View</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else: ?>
                <tr>
                    <td colspan="6" class="p-2 border text-center">No subscriptions found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
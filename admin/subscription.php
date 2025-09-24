<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';

// Ensure 'deleted' column exists
$wpdb->query("
    ALTER TABLE $table_name 
    ADD COLUMN IF NOT EXISTS deleted TINYINT(1) NOT NULL DEFAULT 0
");

// Handle single delete / restore / permanent delete
if( isset($_GET['action_type']) && isset($_GET['id']) ) {
    $id = intval($_GET['id']);
    $action_type = sanitize_text_field($_GET['action_type']);

    switch($action_type) {
        case 'delete':
            $wpdb->update($table_name, ['deleted'=>1], ['id'=>$id], ['%d'], ['%d']);
            break;
        case 'restore':
            $wpdb->update($table_name, ['deleted'=>0], ['id'=>$id], ['%d'], ['%d']);
            break;
        case 'permanent_delete':
            $wpdb->delete($table_name, ['id'=>$id], ['%d']);
            break;
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-subscription'));
    exit;
}

// Handle bulk actions
if( isset($_POST['bulk_action']) && isset($_POST['subscription_ids']) && is_array($_POST['subscription_ids']) ) {
    $action = sanitize_text_field($_POST['bulk_action']);
    $ids = array_map('intval', $_POST['subscription_ids']);

    switch($action) {
        case 'trash':
            $wpdb->query("UPDATE $table_name SET deleted=1 WHERE id IN (" . implode(',', $ids) . ")");
            break;
        case 'restore':
            $wpdb->query("UPDATE $table_name SET deleted=0 WHERE id IN (" . implode(',', $ids) . ")");
            break;
        case 'permanent_delete':
            $wpdb->query("DELETE FROM $table_name WHERE id IN (" . implode(',', $ids) . ")");
            break;
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-subscription'));
    exit;
}

// Pagination
$per_page = 10;
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($paged - 1) * $per_page;

// Filter
$filter = isset($_GET['filter']) ? sanitize_text_field($_GET['filter']) : 'all';
$where = ($filter=='trash') ? "WHERE deleted=1" : "WHERE deleted=0";

// Counts
$total_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE deleted=0");
$trash_count = $wpdb->get_var("SELECT COUNT(*) FROM $table_name WHERE deleted=1");

// Fetch subscriptions
$subscriptions = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY created_at DESC LIMIT $offset, $per_page");

// Total pages
$total_pages = ceil(($filter=='trash'? $trash_count : $total_count)/$per_page);

// Include Add Subscription form
include plugin_dir_path( __FILE__ ) . 'action/add-subscription.php';
?>

<div class="subscription">
    <div class="header flex items-center justify-between mb-4">
        <h1 class="text-3xl font-bold text-gray-800">Subscription</h1>
        <button class="page-title-action" onclick="document.querySelector('.add-subscription-form').classList.add('active');">
            Add Subscription
        </button>
    </div>

    <!-- Filters -->
    <div class="filter mb-4 space-x-2">
        <a href="?page=elevate-lms-subscription&filter=all" class="px-3 py-1 border rounded <?php echo ($filter=='all')?'bg-gray-200':''; ?>">
            All (<?php echo $total_count; ?>)
        </a>
        <a href="?page=elevate-lms-subscription&filter=trash" class="px-3 py-1 border rounded <?php echo ($filter=='trash')?'bg-gray-200':''; ?>">
            Trash (<?php echo $trash_count; ?>)
        </a>
    </div>

    <!-- Bulk Actions -->
    <form method="post">
        <div class="action mb-4 flex items-center space-x-2">
            <select name="bulk_action" id="bulk_action" class="border rounded px-2 py-1">
                <option value="">Bulk actions</option>
                <?php if($filter=='trash'): ?>
                    <option value="restore">Restore</option>
                    <option value="permanent_delete">Delete Permanently</option>
                <?php else: ?>
                    <option value="trash">Move to Trash</option>
                <?php endif; ?>
            </select>
            <button type="submit" class="bg-gray-700 text-white px-3 py-1 rounded hover:bg-gray-800">
                Apply
            </button>
        </div>

        <!-- Subscription Table -->
        <table class="pricing-table w-full border-collapse" role="table" aria-label="Pricing table">
            <thead>
                <tr class="bg-gray-100 text-left">
                    <th class="p-2 border"><input type="checkbox" id="select-all"></th>
                    <th class="p-2 border">Title</th>
                    <th class="p-2 border">Duration</th>
                    <th class="p-2 border">Offer Price</th>
                    <th class="p-2 border">Regular Price</th>
                    <th class="p-2 border">Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($subscriptions): ?>
                    <?php foreach($subscriptions as $sub): 
                        $features = json_decode($sub->features, true); ?>
                        <tr id="sub-<?php echo $sub->id; ?>">
                            <td class="p-2 border">
                                <input type="checkbox" name="subscription_ids[]" value="<?php echo $sub->id; ?>">
                            </td>
                            <td class="p-2 border"><?php echo esc_html($sub->title); ?></td>
                            <td class="p-2 border"><?php echo esc_html(ucfirst($sub->duration)); ?></td>
                            <td class="p-2 border text-red-600 font-bold">৳<?php echo number_format($sub->offer_price, 2); ?></td>
                            <td class="p-2 border line-through text-gray-500">৳<?php echo number_format($sub->regular_price, 2); ?></td>
                            <td class="p-2 border space-x-2">
                                <?php if($filter=='trash'): ?>
                                    <a href="?page=elevate-lms-subscription&action_type=restore&id=<?php echo $sub->id; ?>" class="bg-green-600 text-white px-2 py-1 rounded hover:bg-green-700">Restore</a>
                                    <a href="?page=elevate-lms-subscription&action_type=permanent_delete&id=<?php echo $sub->id; ?>" class="bg-red-700 text-white px-2 py-1 rounded hover:bg-red-800" onclick="return confirm('Are you sure to permanently delete this subscription?')">Delete Permanently</a>
                                <?php else: ?>
                                    <a href="?page=elevate-lms-subscription&action_type=delete&id=<?php echo $sub->id; ?>" class="bg-red-600 text-white px-2 py-1 rounded hover:bg-red-700" onclick="return confirm('Are you sure to move this subscription to Trash?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="p-2 border text-center">No subscriptions found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>

    <!-- Pagination -->
    <div class="pagination mt-4 flex space-x-2">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <a href="?page=elevate-lms-subscription&filter=<?php echo $filter; ?>&paged=<?php echo $i; ?>" class="px-3 py-1 border rounded <?php echo ($paged==$i)?'bg-gray-300':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<script>
document.getElementById('select-all').addEventListener('change', function(){
    const checked = this.checked;
    document.querySelectorAll('input[name="subscription_ids[]"]').forEach(function(cb){
        cb.checked = checked;
    });
});
</script>
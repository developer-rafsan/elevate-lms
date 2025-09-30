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
    <div class="header">
        <h1>Subscription</h1>
        <button class="page-title-action"
            onclick="document.querySelector('.add-subscription-form').classList.add('active');">
            Add Subscription
        </button>
    </div>

    <!-- Filters -->
    <div class="filter">
        <a href="?page=elevate-lms-subscription&filter=all" class="<?php echo ($filter=='all')?'active':''; ?>">
            All (<?php echo $total_count; ?>)
        </a>
        <a href="?page=elevate-lms-subscription&filter=trash" class="<?php echo ($filter=='trash')?'active':''; ?>">
            Trash (<?php echo $trash_count; ?>)
        </a>
    </div>

    <!-- Bulk Actions -->
    <form method="post">
        <div class="action">
            <select name="bulk_action" id="bulk_action">
                <option value="">Bulk actions</option>
                <?php if($filter=='trash'): ?>
                <option value="restore">Restore</option>
                <option value="permanent_delete">Delete Permanently</option>
                <?php else: ?>
                <option value="trash">Move to Trash</option>
                <?php endif; ?>
            </select>
            <button type="submit">
                Apply
            </button>
        </div>


        <!-- Subscription Table -->
        <table class="pricing-table">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Title</th>
                    <th>Duration</th>
                    <th>Offer Price</th>
                    <th>Regular Price</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <?php if($subscriptions): ?>
                <?php foreach($subscriptions as $sub): 
                        $features = json_decode($sub->features, true); ?>
                <tr id="sub-<?php echo $sub->id; ?>">
                    <td>
                        <input type="checkbox" name="subscription_ids[]" value="<?php echo $sub->id; ?>">
                    </td>
                    <td><?php echo esc_html($sub->title); ?></td>
                    <td><?php echo esc_html(ucfirst($sub->duration)); ?></td>
                    <td class="offer">৳<?php echo number_format($sub->offer_price, 2); ?></td>
                    <td class="regular">৳<?php echo number_format($sub->regular_price, 2); ?></td>
                    <td>
                        <?php if($filter=='trash'): ?>
                        <a href="?page=elevate-lms-subscription&action_type=restore&id=<?php echo $sub->id; ?>"
                            class="restore">Restore</a>
                        <a href="?page=elevate-lms-subscription&action_type=permanent_delete&id=<?php echo $sub->id; ?>"
                            class="delete"
                            onclick="return confirm('Are you sure to permanently delete this subscription?')">Delete
                            Permanently</a>
                        <?php else: ?>
                        <a href="?page=elevate-lms-subscription&action_type=delete&id=<?php echo $sub->id; ?>"
                            class="delete"
                            onclick="return confirm('Are you sure to move this subscription to Trash?')">Delete</a>
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
                <?php else: ?>
                <tr>
                    <td colspan="7">No subscriptions found.</td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>

    <!-- Pagination -->
    <div class="pagination">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
        <a href="?page=elevate-lms-subscription&filter=<?php echo $filter; ?>&paged=<?php echo $i; ?>"
            class="<?php echo ($paged==$i)?'active':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<script>
document.getElementById('select-all').addEventListener('change', function() {
    const checked = this.checked;
    document.querySelectorAll('input[name="subscription_ids[]"]').forEach(function(cb) {
        cb.checked = checked;
    });
});
</script>
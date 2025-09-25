<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_categories';

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
    wp_redirect(admin_url('admin.php?page=elevate-lms-categories'));
    exit;
}

// Handle bulk actions
if( isset($_POST['bulk_action']) && isset($_POST['category_ids']) && is_array($_POST['category_ids']) ) {
    $action = sanitize_text_field($_POST['bulk_action']);
    $ids = array_map('intval', $_POST['category_ids']);

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
    wp_redirect(admin_url('admin.php?page=elevate-lms-categories'));
    exit;
}

// Handle add form submission
if ( isset( $_POST['add_category'] ) && isset( $_POST['add_category_nonce'] ) && wp_verify_nonce( $_POST['add_category_nonce'], 'add_category_nonce' ) ) {
    $category_name = sanitize_text_field( $_POST['category_name'] );
    if ( ! empty( $category_name ) ) {
        $wpdb->insert(
            $table_name,
            array(
                'category_name' => $category_name,
            )
        );
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-categories'));
    exit;
}

// Handle edit form submission
if ( isset( $_POST['edit_category'] ) && isset( $_POST['edit_category_nonce'] ) && wp_verify_nonce( $_POST['edit_category_nonce'], 'edit_category_nonce' ) ) {
    $category_id = sanitize_text_field( $_POST['category_id'] );
    $category_name = sanitize_text_field( $_POST['category_name'] );
    if ( ! empty( $category_name ) ) {
        $wpdb->update(
            $table_name,
            array(
                'category_name' => $category_name,
            ),
            array(
                'id' => $category_id,
            )
        );
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-categories'));
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

// Fetch categories
$categories = $wpdb->get_results("SELECT * FROM $table_name $where ORDER BY id DESC LIMIT $offset, $per_page");

// Total pages
$total_pages = ceil(($filter=='trash'? $trash_count : $total_count)/$per_page);

// Include Add Category form
include plugin_dir_path( __FILE__ ) . 'action/add-category.php';

if (isset($_GET['action']) && $_GET['action'] == 'edit') {
    include plugin_dir_path( __FILE__ ) . 'action/edit-category.php';
}

?>

<div class="wrap categories">
    <div class="header">
        <h1>Categories</h1>
        <button class="page-title-action" onclick="document.querySelector('.add-category-form').classList.add('active');">
            Add New Category
        </button>
    </div>

    <!-- Filters -->
    <div class="filter">
        <a href="?page=elevate-lms-categories&filter=all" class="<?php echo ($filter=='all')?'active':''; ?>">
            All (<?php echo $total_count; ?>)
        </a>
        <a href="?page=elevate-lms-categories&filter=trash" class="<?php echo ($filter=='trash')?'active':''; ?>">
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

        <!-- Categories Table -->
        <table class="wp-list-table widefat fixed striped">
            <thead>
                <tr>
                    <th><input type="checkbox" id="select-all"></th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php if($categories): ?>
                    <?php foreach($categories as $cat): ?>
                        <tr id="cat-<?php echo $cat->id; ?>">
                            <td>
                                <input type="checkbox" name="category_ids[]" value="<?php echo $cat->id; ?>">
                            </td>
                            <td><?php echo esc_html($cat->category_name); ?></td>
                            <td>
                                <?php if($filter=='trash'): ?>
                                    <a href="?page=elevate-lms-categories&action_type=restore&id=<?php echo $cat->id; ?>" class="restore">Restore</a>
                                    <a href="?page=elevate-lms-categories&action_type=permanent_delete&id=<?php echo $cat->id; ?>" class="delete" onclick="return confirm('Are you sure to permanently delete this category?')">Delete Permanently</a>
                                <?php else: ?>
                                    <a href="?page=elevate-lms-categories&action_type=delete&id=<?php echo $cat->id; ?>" class="delete" onclick="return confirm('Are you sure to move this category to Trash?')">Delete</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="3">No categories found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </form>

    <!-- Pagination -->
    <div class="pagination">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <a href="?page=elevate-lms-categories&filter=<?php echo $filter; ?>&paged=<?php echo $i; ?>" class="<?php echo ($paged==$i)?'active':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

<script>
document.getElementById('select-all').addEventListener('change', function(){
    const checked = this.checked;
    document.querySelectorAll('input[name="category_ids[]"]').forEach(function(cb){
        cb.checked = checked;
    });
});
</script>
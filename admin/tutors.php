<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_tutors';

// Handle single delete
if( isset($_GET['action_type']) && isset($_GET['id']) ) {
    $user_id = intval($_GET['id']);
    $action_type = sanitize_text_field($_GET['action_type']);

    if ($action_type === 'delete' && $user_id > 0) {
        // Change user role back to 'subscriber'
        wp_update_user(array('ID' => $user_id, 'role' => 'subscriber'));
        
        // Also remove from the custom table if exists
        $wpdb->delete($table_name, ['email' => get_userdata($user_id)->user_email], ['%s']);
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-tutors'));
    exit;
}

// Handle add form submission
if ( isset( $_POST['add_tutor'] ) && isset( $_POST['add_tutor_nonce'] ) && wp_verify_nonce( $_POST['add_tutor_nonce'], 'add_tutor_nonce' ) ) {
    $user_id = intval( $_POST['tutor_id'] );
    if ( $user_id > 0 ) {
        // Change user role to 'author'
        wp_update_user( array( 'ID' => $user_id, 'role' => 'author' ) );

        // Get user data
        $user_data = get_userdata( $user_id );

        // Check if tutor already exists in the custom table
        $existing_tutor = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE email = %s", $user_data->user_email ) );

        if ( ! $existing_tutor ) {
            // Insert tutor into custom table
            $wpdb->insert(
                $table_name,
                array(
                    'name' => $user_data->display_name,
                    'email' => $user_data->user_email,
                )
            );
        }
    }
    wp_redirect(admin_url('admin.php?page=elevate-lms-tutors'));
    exit;
}

// Pagination
$per_page = 10;
$paged = isset($_GET['paged']) ? max(1, intval($_GET['paged'])) : 1;
$offset = ($paged - 1) * $per_page;

// Counts
$user_counts = count_users();
$total_count = isset($user_counts['avail_roles']['author']) ? $user_counts['avail_roles']['author'] : 0;

// Fetch author users
$tutors = get_users(array('role' => 'author', 'offset' => $offset, 'number' => $per_page));

// Total pages
$total_pages = ceil($total_count / $per_page);

// Include Add Tutor form
include plugin_dir_path( __FILE__ ) . 'action/add-tutor.php';

?>

<div class="wrap tutors">
    <div class="header">
        <h1>Tutors</h1>
        <button class="page-title-action" onclick="document.querySelector('.add-tutor-form').classList.add('active');">
            Add New Tutor
        </button>
    </div>

    <!-- Tutors Table -->
    <table class="wp-list-table widefat fixed striped">
        <thead>
            <tr>
                <th>Name</th>
                <th>Email</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($tutors)) : ?>
                <?php foreach ($tutors as $tutor) : ?>
                    <tr id="user-<?php echo $tutor->ID; ?>">
                        <td><?php echo esc_html($tutor->display_name); ?></td>
                        <td><?php echo esc_html($tutor->user_email); ?></td>
                        <td>
                            <a href="?page=elevate-lms-tutors&action_type=delete&id=<?php echo $tutor->ID; ?>" class="delete" onclick="return confirm('This action will remove the author role from this user and revert them to a subscriber. Are you sure?')">Delete</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="3">No tutors found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <div class="pagination">
        <?php for($i=1; $i<=$total_pages; $i++): ?>
            <a href="?page=elevate-lms-tutors&paged=<?php echo $i; ?>" class="<?php echo ($paged==$i)?'active':''; ?>"><?php echo $i; ?></a>
        <?php endfor; ?>
    </div>
</div>

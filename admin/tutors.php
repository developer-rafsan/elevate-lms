<?php
defined('ABSPATH') or die('No script kiddies please!');

global $wpdb;

// Handle form submission
if (isset($_POST['assign_tutor'])) {
    $user_id = intval($_POST['user_id']);
    if ($user_id) {
        $user = new WP_User($user_id);
        $user->set_role('author'); 
        echo '<div class="notice notice-success is-dismissible"><p>Tutor assigned successfully.</p></div>';
    }
}

// ✅ Only get users with role subscriber
$all_users = get_users([
    'role'    => 'subscriber',
    'orderby' => 'display_name',
    'order'   => 'ASC'
]);

// Get all current tutors (authors)
$tutors = get_users([
    'role'    => 'author',
    'orderby' => 'display_name',
    'order'   => 'ASC',
]);
?>

<div class="wrap tutors">
    <div class="header mb-4">
        <h1 class="text-2xl font-bold">Manage Tutors</h1>
    </div>

    <div style="display: flex; gap:20px; margin-top: 20px;">
        <!-- Tutors Table -->
        <table style="width: 70%;" class="widefat fixed striped">
            <thead>
                <tr>
                    <th><?php esc_html_e('ID', 'pixelcode'); ?></th>
                    <th><?php esc_html_e('Name', 'pixelcode'); ?></th>
                    <th><?php esc_html_e('Username', 'pixelcode'); ?></th>
                    <th><?php esc_html_e('Email', 'pixelcode'); ?></th>
                    <th><?php esc_html_e('Registered Date', 'pixelcode'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php if ($tutors): ?>
                    <?php foreach($tutors as $tutor): ?>
                    <tr>
                        <td><?php echo esc_html($tutor->ID); ?></td>
                        <td><?php echo esc_html($tutor->display_name); ?></td>
                        <td><?php echo esc_html($tutor->user_login); ?></td>
                        <td><?php echo esc_html($tutor->user_email); ?></td>
                        <td><?php echo esc_html(date('Y-m-d', strtotime($tutor->user_registered))); ?></td>
                    </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="5" class="text-center"><?php esc_html_e('No tutors found.', 'pixelcode'); ?></td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>

        <!-- Assign Tutor Form -->
        <div style="width: 20%; padding:20px; box-shadow: 5px 5px 10px rgba(0, 0, 0, 0.1);" class="add-tutors-form mb-6">
            <form method="post">
                <label style="font-size: 1.2rem; font-weight: 600" for="user_id" class="block mb-2 font-bold">Select Subscriber to Assign as Tutor:</label>
                <select style="margin: 20px 0px" name="user_id" id="user_id" class="regular-text mb-2">
                    <option value="">-- Select Subscriber --</option>
                    <?php foreach ($all_users as $user): ?>
                        <option value="<?php echo esc_attr($user->ID); ?>">
                            <?php echo esc_html($user->display_name . ' (' . $user->user_email . ')'); ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <br>
                <input type="submit" name="assign_tutor" class="button button-primary mt-2" value="Save Now">
            </form>
        </div>
    </div>
</div>

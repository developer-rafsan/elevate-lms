<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

// Get all users with the 'subscriber' role
$subscribers = get_users(array('role' => 'subscriber'));

?>

<div class="add-tutor-form">
    <div class="card">
        <div class="card-header">
            <h5><?php echo esc_html__('Add New Tutor', 'pixelcode'); ?></h5>
            <button onclick="document.querySelector('.add-tutor-form').classList.remove('active');">
                &times;
            </button>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo esc_url(admin_url('admin.php?page=elevate-lms-tutors')); ?>">
                <div class="form-field">
                    <label for="tutor_id"><?php echo esc_html__('Select User', 'pixelcode'); ?></label>
                    <select name="tutor_id" id="tutor_id" required>
                        <option value=""><?php echo esc_html__('Select a user', 'pixelcode'); ?></option>
                        <?php foreach ($subscribers as $subscriber) : ?>
                            <option value="<?php echo esc_attr($subscriber->ID); ?>">
                                <?php echo esc_html($subscriber->display_name); ?> (<?php echo esc_html($subscriber->user_email); ?>)
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <?php wp_nonce_field('add_tutor_nonce', 'add_tutor_nonce'); ?>
                <p class="submit">
                    <input type="submit" name="add_tutor" class="button button-primary" value="<?php echo esc_attr__('Add Tutor', 'pixelcode'); ?>">
                </p>
            </form>
        </div>
    </div>
</div>
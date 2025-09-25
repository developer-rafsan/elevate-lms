<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>

<div class="add-category-form">
    <div class="card">
        <div class="card-header">
            <h5><?php echo esc_html__( 'Add New Category', 'pixelcode' ); ?></h5>
            <button type="button" onclick="document.querySelector('.add-category-form').classList.remove('active');">
                &times;
            </button>
        </div>
        <div class="card-body">
            <form method="post" action="<?php echo esc_url( admin_url( 'admin.php?page=elevate-lms-categories' ) ); ?>">
                <div class="form-field">
                    <label for="category_name"><?php echo esc_html__( 'Category Name', 'pixelcode' ); ?></label>
                    <input type="text" name="category_name" id="category_name" required>
                </div>
                <?php wp_nonce_field( 'add_category_nonce', 'add_category_nonce' ); ?>
                <p class="submit">
                    <input type="submit" name="add_category" class="button button-primary" value="<?php echo esc_attr__( 'Add Category', 'pixelcode' ); ?>">
                </p>
            </form>
        </div>
    </div>
</div>

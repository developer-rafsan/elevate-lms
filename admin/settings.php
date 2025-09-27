<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );
?>
<div class="wrap">
    <h1><?php echo esc_html__( 'Elevate LMS Settings', 'pixelcode' ); ?></h1>
    <form method="post" action="options.php">
        <?php
        settings_fields( 'elevate_lms_settings_group' );
        do_settings_sections( 'elevate-lms-settings' );
        submit_button();
        ?>
    </form>
</div>

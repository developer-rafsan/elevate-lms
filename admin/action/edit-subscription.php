<?php
defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;
$table_name = $wpdb->prefix . 'elevate_lms_subscriptions';
$id = intval( $_GET['id'] );

if ( isset( $_POST['update_subscription'] ) ) {
    $title = sanitize_text_field($_POST['title']);
    $offer_price = floatval($_POST['offer_price']);
    $regular_price = floatval($_POST['regular_price']);
    $duration = sanitize_text_field($_POST['duration']);
    $features = sanitize_textarea_field($_POST['features']);

    $wpdb->update(
        $table_name,
        array(
            'title' => $title,
            'offer_price' => $offer_price,
            'regular_price' => $regular_price,
            'duration' => $duration,
            'features' => $features,
        ),
        array( 'id' => $id )
    );
    ?>
    <div class="notice notice-success is-dismissible">
        <p>Subscription updated successfully!</p>
    </div>
    <?php
}

$subscription = $wpdb->get_row( $wpdb->prepare( "SELECT * FROM $table_name WHERE id = %d", $id ) );

?>

<div class="wrap">
    <h1 class="wp-heading-inline">Edit Subscription</h1>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
                <tr>
                    <th scope="row"><label for="title">Title</label></th>
                    <td><input name="title" type="text" id="title" value="<?php echo esc_attr( $subscription->title ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="duration">Duration</label></th>
                    <td><input name="duration" type="text" id="duration" value="<?php echo esc_attr( $subscription->duration ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="offer_price">Offer Price</label></th>
                    <td><input name="offer_price" type="number" id="offer_price" value="<?php echo esc_attr( $subscription->offer_price ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="regular_price">Regular Price</label></th>
                    <td><input name="regular_price" type="number" id="regular_price" value="<?php echo esc_attr( $subscription->regular_price ); ?>" class="regular-text"></td>
                </tr>
                <tr>
                    <th scope="row"><label for="features">Features</label></th>
                    <td><textarea name="features" id="features" class="large-text" rows="5"><?php echo esc_textarea( $subscription->features ); ?></textarea></td>
                </tr>
            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="update_subscription" id="submit" class="button button-primary" value="Update Subscription">
        </p>
    </form>
</div>

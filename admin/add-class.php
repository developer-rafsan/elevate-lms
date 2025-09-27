<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

global $wpdb;

// Subscriptions table name
$subscriptions_table = $wpdb->prefix . 'elevate_lms_subscriptions';

// Get all subscriptions
$subscriptions = $wpdb->get_results( "SELECT id, title, duration, offer_price, regular_price FROM $subscriptions_table ORDER BY id ASC", ARRAY_A );

?>
<div class="wrap add-class">
    <div class="header">
        <h3>Elevate LMS</h3>
        <button>public</button>
    </div>

    <form method="post" action="<?php echo esc_url(admin_url('admin-post.php')); ?>">
        <input type="hidden" name="action" value="add_class_action">
        <?php wp_nonce_field('add_class_action', 'add_class_nonce'); ?>

        <div class="elevate-lms-course-builder">
            <!-- left site -->
            <div class="left-site">
                <!-- Title -->
                <div class="form-field">
                    <label for="course_title">Title</label>
                    <input type="text" id="course_title" name="course_title" required>
                    <p class="description">Enter the course name.</p>
                </div>

                <!-- Description -->
                <div class="form-field">
                    <label for="course_description">Description</label>
                    <?php
                        $content   = '';
                        $editor_id = 'course_description';
                        $settings  = array(
                            'media_buttons' => true,
                            'textarea_name' => 'course_description',
                            'textarea_rows' => 10,
                            'teeny'         => false,
                            'quicktags'     => true,
                        );
                        wp_editor( $content, $editor_id, $settings );
                    ?>
                </div>

                <div class="option-box">
                    <div class="tab-buttons">
                        <button type="button" class="active" data-tab="difficulty">Difficulty</button>
                        <button type="button" data-tab="pricing">Pricing</button>
                        <button type="button" data-tab="duration">Duration</button>
                        <button type="button" data-tab="classes">Classes Type</button>
                    </div>

                    <div class="tab-content">
                        <!-- Difficulty Level -->
                        <div class="tab-item active" data-tab="difficulty">
                            <label for="difficulty">Difficulty Level</label>
                            <select name="difficulty" id="difficulty">
                                <option value="beginner">Beginner</option>
                                <option value="intermediate">Intermediate</option>
                                <option value="advanced">Advanced</option>
                                <option value="custom">Custom</option>
                            </select>

                            <!-- hidden custom input -->
                            <div id="custom-difficulty-box" style="display:none; margin-top:10px;">
                                <label for="custom_difficulty">Enter Custom Difficulty:</label>
                                <input type="text" id="custom_difficulty" name="custom_difficulty"
                                    placeholder="Your custom level">
                            </div>
                        </div>


                        <!-- Pricing Model -->
                        <div class="tab-item" data-tab="pricing">
                            <label>Pricing Model</label>
                            <label><input type="radio" name="pricing" value="free" checked> Free</label>
                            <label><input type="radio" name="pricing" value="paid"> Paid</label>

                            <!-- hidden subscription select box -->
                            <div id="subscription-box" style="display:none; margin-top:10px;">
                                <label for="subscription_id">Choose Subscription:</label>
                                <select name="subscription_id" id="subscription_id">
                                    <option value="">-- Select Subscription --</option>
                                    <?php if ( $subscriptions ) : ?>
                                    <?php foreach ( $subscriptions as $sub ) : ?>
                                    <option value="<?php echo esc_attr($sub['id']); ?>">
                                        <?php echo esc_html($sub['title']); ?> - 
                                        <?php echo esc_html($sub['duration']); ?> (Offer:
                                        $<?php echo esc_html($sub['offer_price']); ?>, Regular:
                                        $<?php echo esc_html($sub['regular_price']); ?>)
                                    </option>
                                    <?php endforeach; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>


                        <!-- Duration Tab -->
                        <div class="tab-item" data-tab="duration">
                            <div class="duration-box">
                                <!-- Duration -->
                                <div class="form-field">
                                    <label for="duration">Duration</label>
                                    <input type="text" id="duration" name="duration" placeholder="e.g., 3 hours, 4 weeks">
                                </div>
                                <!-- Start Time -->
                                <div class="time-group">
                                    <label for="start_date">Start Time</label>
                                    <div class="time-inputs">
                                        <input type="date" id="start_date" name="start_date">
                                        <input type="time" id="start_time" name="start_time">
                                    </div>
                                    <small class="hint">Select when your course starts</small>
                                </div>

                                <!-- End Time -->
                                <div class="time-group">
                                    <label for="end_date">End Time</label>
                                    <div class="time-inputs">
                                        <input type="date" id="end_date" name="end_date">
                                        <input type="time" id="end_time" name="end_time">
                                    </div>
                                    <small class="hint">Select when your course ends</small>
                                </div>
                            </div>
                        </div>

                        <!-- Classes Tab -->
                        <div class="tab-item" data-tab="classes">
                            <div class="form-field">
                                <label><input type="radio" name="class_type" value="pre-recorded" checked> Pre-recorded</label>
                                <label><input type="radio" name="class_type" value="live"> Live Class</label>
                            </div>

                            <div class="form-field" id="pre-recorded-video-box">
                                <label>Pre-recorded Video</label>
                                <button type="button" class="button upload-pre-recorded-video">Upload Video</button>
                                <input type="hidden" name="pre_recorded_video" id="pre_recorded_video">
                                <div id="pre_recorded_video_preview"></div>
                            </div>

                            <div class="form-field" id="zoom-link-box" style="display:none;">
                                <p>The Zoom meeting link will be automatically generated upon class creation.</p>
                            </div>
                        </div>


                    </div>
                </div>
                <!-- class pre requrment -->
                <div class="form-field requrment-field">
                    <label for="requrment">Pre Requrment</label>
                    <input type="text" id="requrment" name="requrment" required>
                </div>

            </div>

            <!-- right site -->
            <div class="right-site">
                <!-- Featured Image -->
                <div class="form-field">
                    <label>Featured Image</label>
                    <button type="button" class="button upload-featured-image">Upload Thumbnail</button>
                    <input type="hidden" name="featured_image" id="featured_image">
                    <div id="featured_image_preview"></div>
                </div>

                <!-- Intro Video -->
                <div class="form-field">
                    <label>Intro Video</label>
                    <button type="button" class="button upload-intro-video">Upload Video</button>
                    <input type="hidden" name="intro_video" id="intro_video">
                    <div id="intro_video_preview"></div>
                </div>

                <!-- Categories -->
                <div class="form-field">
                    <label>Categories</label>
                    <select name="categories">
                        <option value="">-- Select Categories --</option>
                        <?php
                        global $wpdb;

                        // Categories table name
                        $categories_table = $wpdb->prefix . 'elevate_lms_categories';

                        // Fetch all categories where deleted = 0
                        $categories = $wpdb->get_results( "SELECT id, category_name FROM $categories_table WHERE deleted = 0 ORDER BY category_name ASC", ARRAY_A );

                        if ( $categories ) :
                            foreach ( $categories as $cat ) :
                        ?>
                        <option value="<?php echo esc_attr( $cat['id'] ); ?>">
                            <?php echo esc_html( $cat['category_name'] ); ?>
                        </option>
                        <?php
                            endforeach;
                        else :
                        ?>
                        <option disabled>No categories found</option>
                        <?php endif; ?>
                    </select>
                </div>
                
                <!-- Tutor -->
                <div class="form-field">
                    <label for="tutor_id">Tutor</label>
                    <select name="tutor_id" id="tutor_id">
                        <option value="">-- Select Tutor --</option>
                        <?php
                        // Get all authors
                        $args = array(
                            'role__in' => array( 'author' ), 
                            'orderby'  => 'display_name',
                            'order'    => 'ASC'
                        );
                        $authors = get_users( $args );

                        if ( $authors ) :
                            foreach ( $authors as $author ) :
                        ?>
                            <option value="<?php echo esc_attr( $author->ID ); ?>">
                                <?php echo esc_html( $author->display_name ); ?>
                            </option>
                        <?php
                            endforeach;
                        else :
                        ?>
                            <option disabled>No tutors (authors) found</option>
                        <?php endif; ?>
                    </select>
                </div>
                <p class="submit">
                    <input type="submit" name="add_class" class="button button-primary" value="<?php echo esc_attr__('Add Class', 'pixelcode'); ?>">
                </p>
            </div>
        </div>
    </form>
</div>
<?php
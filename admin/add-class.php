<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

?>
<div class="wrap add-class">
    <div class="header">
        <h3>Elevate LMS</h3>
        <button>public</button>
    </div>

    <form action="">
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

                <!-- Difficulty Level -->
                <div class="form-field">
                    <label for="difficulty">Difficulty Level</label>
                    <select name="difficulty" id="difficulty">
                        <option value="beginner">Beginner</option>
                        <option value="intermediate">Intermediate</option>
                        <option value="advanced">Advanced</option>
                    </select>
                </div>

                <!-- Pricing Model -->
                <div class="form-field">
                    <label>Pricing Model</label><br>
                    <label><input type="radio" name="pricing" value="free" checked> Free</label>
                    <label><input type="radio" name="pricing" value="paid"> Paid</label>
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
                    <select name="categories[]" multiple>
                        <?php
                global $wpdb;
                $categories = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}elevate_lms_categories WHERE deleted=0");
                if ( $categories ) {
                    foreach ( $categories as $cat ) {
                        echo '<option value="' . esc_attr($cat->id) . '">' . esc_html($cat->category_name) . '</option>';
                    }
                } else {
                    echo '<option disabled>No categories found</option>';
                }
                ?>
                    </select>
                </div>
            </div>
        </div>
    </form>
    <?php
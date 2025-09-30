// Admin JS

let featureCount = 1;

const addFeatureButton = document.getElementById('add-feature');
if (addFeatureButton) {
    addFeatureButton.addEventListener('click', function () {
        featureCount++;
        const wrapper = document.getElementById('features-wrapper');

        // Create new div
        const newDiv = document.createElement('div');
        newDiv.classList.add('mb-3', 'feature-item');

        // Create label
        const newLabel = document.createElement('label');
        newLabel.classList.add('form-label');
        newLabel.setAttribute('for', 'features-' + featureCount);
        newLabel.innerText = 'Features';

        // Create input
        const newInput = document.createElement('input');
        newInput.type = 'text';
        newInput.name = 'features[]'; // array format
        newInput.id = 'features-' + featureCount;
        newInput.classList.add('form-control', 'mb-2');

        // Append label and input to div
        newDiv.appendChild(newLabel);
        newDiv.appendChild(newInput);

        // Append new div to wrapper
        wrapper.appendChild(newDiv);
    });
}

jQuery(document).ready(function ($) {
    $('.tab-buttons button').on('click', function () {
        let tab_id = $(this).data('tab');

        // button active handle
        $('.tab-buttons button').removeClass('active');
        $(this).addClass('active');

        // content active handle
        $('.tab-content .tab-item').removeClass('active');
        $('.tab-content .tab-item[data-tab="' + tab_id + '"]').addClass('active');
    });

    $('#difficulty').on('change', function () {
        if ($(this).val() === 'custom') {
            $('#custom-difficulty-box').show();
        } else {
            $('#custom-difficulty-box').hide();
        }
    });

    $('input[name="pricing"]').on('change', function () {
        if ($(this).val() === 'paid') {
            $('#subscription-box').show();
        } else {
            $('#subscription-box').hide();
        }
    });

    // Featured Image Upload
    var featured_frame;
    $('.upload-featured-image').on('click', function (e) {
        e.preventDefault();
        if (featured_frame) {
            featured_frame.open();
            return;
        }
        featured_frame = wp.media({
            title: 'Select Featured Image',
            button: { text: 'Use This Image' },
            multiple: false
        });

        featured_frame.on('select', function () {
            var attachment = featured_frame.state().get('selection').first().toJSON();
            $('#featured_image').val(attachment.id);
            $('#featured_image_preview').html('<img src="' + attachment.url + '" alt="Featured Image">');
        });

        featured_frame.open();
    });

    // Intro Video Upload
    var video_frame;
    $('.upload-intro-video').on('click', function (e) {
        e.preventDefault();
        if (video_frame) {
            video_frame.open();
            return;
        }
        video_frame = wp.media({
            title: 'Select Intro Video',
            button: { text: 'Use This Video' },
            multiple: false,
            library: { type: 'video' }
        });

        video_frame.on('select', function () {
            var attachment = video_frame.state().get('selection').first().toJSON();
            $('#intro_video').val(attachment.id);
            $('#intro_video_preview').html('<video controls src="' + attachment.url + '"></video>');
        });

        video_frame.open();
    });
});

document.addEventListener('DOMContentLoaded', function () {
    const classTypeRadios = document.querySelectorAll('input[name="class_type"]');
    const preRecordedBox = document.getElementById('pre-recorded-video-box');
    const zoomLinkBox = document.getElementById('zoom-link-box');

    classTypeRadios.forEach(radio => {
        radio.addEventListener('change', function () {
            if (this.value === 'live') {
                preRecordedBox.style.display = 'none';
                zoomLinkBox.style.display = 'block';
            } else {
                preRecordedBox.style.display = 'block';
                zoomLinkBox.style.display = 'none';
            }
        });
    });
});




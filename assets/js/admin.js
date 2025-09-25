// Admin JS

let featureCount = 1;

const addFeatureButton = document.getElementById('add-feature');
if (addFeatureButton) {
    addFeatureButton.addEventListener('click', function() {
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
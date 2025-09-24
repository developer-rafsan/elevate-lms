<?php

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

?>
<div class="subscription">
    <div class="header">
        <h1 class="text-3xl font-bold text-gray-800 mb-4">Subscription</h1>
        <button type="button">Add Subscription</button>
    </div>

    <div class="filter">
        <button class="active">All (10)</button>
        <button>Published (5)</button>
        <button>Trash (1)</button>
    </div>

    <div class="action">
        <select name="actions" id="actions">
            <option>Bulk actions</option>
            <option value="edit">Edit</option>
            <option value="trash">Move to Trash</option>
        </select>
        <button>
            <p>Apply</p>
        </button>
    </div>

    <table class="pricing-table" role="table" aria-label="Pricing table">
        <thead>
            <tr>
                <th>Title</th>
                <th>Duration</th>
                <th>Offer Price</th>
                <th>Regular Price</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td data-label="Title">Beginner Course</td>
                <td data-label="Duration">3 Months</td>
                <td data-label="Offer Price" class="price offer">৳2,500</td>
                <td data-label="Regular Price" class="price regular">৳4,000</td>
                <td data-label="Action">
                    <a class="action-btn" href="/purchase?item=beginner" rel="noopener">Edit</a>
                    <a class="action-btn" href="/purchase?item=beginner" rel="noopener">Delete</a>
                    <a class="action-btn" href="/purchase?item=beginner" rel="noopener">view</a>
                </td>
            </tr>
        </tbody>
    </table>

</div>
<?php
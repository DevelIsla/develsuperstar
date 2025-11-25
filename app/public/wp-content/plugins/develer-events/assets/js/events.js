jQuery(document).ready(function($) {
    'use strict';
    
    // Handle filter form submission
    $('#events-filter-form').on('submit', function(e) {
        e.preventDefault();
        
        // Get form values
        var searchTerm = $('#event-search').val();
        var category = $('#event-category').val();
        var dateFrom = $('#event-date-from').val();
        var dateTo = $('#event-date-to').val();
        
        // Build URL with parameters
        var baseUrl = window.location.origin + window.location.pathname;
        var params = [];
        
        if (searchTerm) {
            params.push('s=' + encodeURIComponent(searchTerm));
        }
        
        if (category && category !== '-1') {
            params.push('event_category=' + category);
        }
        
        if (dateFrom) {
            params.push('date_from=' + dateFrom);
        }
        
        if (dateTo) {
            params.push('date_to=' + dateTo);
        }
        
        // Redirect to filtered URL
        var finalUrl = baseUrl;
        if (params.length > 0) {
            finalUrl += '?' + params.join('&');
        }
        
        window.location.href = finalUrl;
    });
    
    // Optional: Add date range validation
    $('#event-date-to').on('change', function() {
        var dateFrom = $('#event-date-from').val();
        var dateTo = $(this).val();
        
        if (dateFrom && dateTo && dateTo < dateFrom) {
            alert('End date must be after start date');
            $(this).val('');
        }
    });
    
    // Optional: Auto-submit on category change
    $('#event-category').on('change', function() {
        $('#events-filter-form').submit();
    });
});

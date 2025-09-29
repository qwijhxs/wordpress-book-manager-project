jQuery(document).ready(function($) {

    $('#book_year').on('blur', function() {
        var year = $(this).val();
        var currentYear = new Date().getFullYear();
        
        if (year && (year < 1000 || year > currentYear)) {
            $(this).addClass('error-field');
            showFieldError($(this), 'Please enter a valid year between 1000 and ' + currentYear);
        } else {
            $(this).removeClass('error-field');
            clearFieldError($(this));
        }
    });

    $('#book_isbn').on('blur', function() {
        var isbn = $(this).val();
        var isbnRegex = /^[\d\-]+$/;
        
        if (isbn && !isbnRegex.test(isbn)) {
            $(this).addClass('error-field');
            showFieldError($(this), 'ISBN can only contain numbers and hyphens');
        } else {
            $(this).removeClass('error-field');
            clearFieldError($(this));
        }
    });

    $('#book_pages').on('blur', function() {
        var pages = $(this).val();
        
        if (pages && pages < 1) {
            $(this).addClass('error-field');
            showFieldError($(this), 'Number of pages must be at least 1');
        } else {
            $(this).removeClass('error-field');
            clearFieldError($(this));
        }
    });

    $('#book_price').on('blur', function() {
        var price = $(this).val();
        
        if (price && price < 0) {
            $(this).addClass('error-field');
            showFieldError($(this), 'Price cannot be negative');
        } else {
            $(this).removeClass('error-field');
            clearFieldError($(this));
        }
    });

    function showFieldError($field, message) {
        clearFieldError($field);
        $field.after('<div class="field-error" style="color: #d63638; font-size: 12px; margin-top: 5px;">' + message + '</div>');
    }

    function clearFieldError($field) {
        $field.siblings('.field-error').remove();
    }

    $('form#post').on('submit', function(e) {
        var hasErrors = $('.error-field').length > 0;
        
        if (hasErrors) {
            e.preventDefault();
            alert('Please fix the validation errors before saving.');
        }
    });
});
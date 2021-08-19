$( document ).ready(function() {
    $('form').on('submit', function() { 
        return confirm('Are you sure you want to cancel the booking?');
    });
});
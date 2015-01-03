$(document).ready(function() {
    var url = window.location.href;
    (function worker() {
        $.ajax({
            url: url,
            success: function(data) {
                //$('.result').html(data);
            },
            complete: function() {
                // Schedule the next request when the current one's complete
                setTimeout(worker, 60000);
            }
        });
    })();
});
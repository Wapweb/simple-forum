// prepare the form when the DOM is ready 
$(document).ready(function() {

    var wbbOpt = {
        buttons: "bold,italic,underline,|link,smilebox,|,code,quote",
        autoresize: false

    }
    $('#textarea_msg').wysibb(wbbOpt);
});
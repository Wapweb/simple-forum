// prepare the form when the DOM is ready 
$(document).ready(function() {
    var options = {
        dataType:  'json',
        success:   processJson,
        beforeSubmit:  showRequest  // pre-submit callback
    };

    var wbbOpt = {
        buttons: "bold,italic,underline,|,link,smilebox,|,code,quote"
    }
    $('#wbbeditor').wysibb(wbbOpt);

    // bind form using 'ajaxForm'
    $('#topic_create').ajaxForm(options);
});

function processJson(data) {
    // 'data' is the json object returned from the server
    var error_status = data.error_status;
    if(error_status == 1) {
        $("#output").html('<div class="alert alert-danger">'+data.error_msg+'</div>');
    } else {
        $("#button").attr('disabled', 'disabled');
        $("#output").html('<div class="alert alert-success" id="suc">'+data.success_msg+'</div>');
        $("suc").focus();
        //window.location = data.url;
        window.setTimeout( function(){
            window.location = data.url;
        }, 1000 );
    }

}

// pre-submit callback
function showRequest(formData, jqForm, options) {
    // formData is an array; here we use $.param to convert it to a string to display it
    // but the form plugin does this for you automatically when it submits the data
    $("#output").text(" ");
    var queryString = $.param(formData);

    var form = jqForm[0];
    var topic_name = form.topic_name.value;
    var message_text = form.message_text.value;
    var error = 0;
    $("#empty_name").empty();
    $("#empty_text").empty();
    if(!topic_name) {
        $("#tn").addClass("has-error");
        $("#tpn").after("<div class='text-danger' id='empty_name'>Введите название темы!</div>");
        error = 1;
    } else {
        $("#tn").removeClass("has-error");
    }

    if(!message_text) {
        $("#mt").addClass("has-error");
        $("#msgt").after("<div class='text-danger' id='empty_text'>Введите сообщение темы!</div>");
        error = 1;
    } else {
        $("#mt").removeClass("has-error");
    }
    if(error == 1) return false;
    else {
        return true;
    }



    // jqForm is a jQuery object encapsulating the form element.  To access the
    // DOM element for the form do this:
    // var formElement = jqForm[0];
    //alert('About to submit: \n\n' + queryString);

    // here we could return false to prevent the form from being submitted;
    // returning anything other than false will allow the form submit to continue
    return true;
}
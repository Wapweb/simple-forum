// prepare the form when the DOM is ready 
$(document).ready(function() {
    var options = {
        target:        '#output',   // target element(s) to be updated with server response
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback
        beforeSend: function() {
            $('#button').append(" <img src='/assets/images/update.gif' alt='update'>");
            $('#button').attr('disabled', true);
        },
        uploadProgress: function() {
        },
        complete: function() {
            $("#button").html("Регистрация");
            $('#button').removeAttr('disabled');
        }
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute
        //dataType:  null        // 'xml', 'script', or 'json' (expected server response type) 
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 

        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    };

    // bind form using 'ajaxForm' 
    $('#reg').ajaxForm(options);
});

// pre-submit callback 
function showRequest(formData, jqForm, options) {
    // formData is an array; here we use $.param to convert it to a string to display it 
    // but the form plugin does this for you automatically when it submits the data 
    var queryString = $.param(formData);

    var form = jqForm[0];
    var password = form.password.value;
    var password_repeat = form.password_repeat.value;
    var email = form.email.value;
    var login = form.login.value;
    var error = 0;
    if(!password) {
        $("#pass").addClass("has-error");
        $("#inputPassword1").attr("placeholder", "Введите пароль!");
        error = 1;
    } else {
        $("#pass").removeClass("has-error");
    }
    if(!password_repeat) {
        $("#pass_repeat").addClass("has-error");
        $("#inputPassword2").attr("placeholder", "Введите пароль повторно!");
        error = 1;
    } else {
        $("#pass_repeat").removeClass("has-error");
    }

    if(!login) {
        $("#login").addClass("has-error");
        $("#inputLogin").attr("placeholder", "Введите логин!");
        error = 1;
    } else {
        $("#login").removeClass("has-error");
    }

    if(!email) {
        $("#email").addClass("has-error");
        $("#inputEmail1").attr("placeholder", "Введите email!");
        error = 1;
    } else {
        $("#email").removeClass("has-error");
    }
    if(error == 1) return false;
    else return true;



    // jqForm is a jQuery object encapsulating the form element.  To access the 
    // DOM element for the form do this: 
    // var formElement = jqForm[0];
    //alert('About to submit: \n\n' + queryString);

    // here we could return false to prevent the form from being submitted; 
    // returning anything other than false will allow the form submit to continue 
    return true;
}

// post-submit callback 
function showResponse(responseText, statusText, xhr, $form)  {
    // for normal html responses, the first argument to the success callback 
    // is the XMLHttpRequest object's responseText property 

    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'xml' then the first argument to the success callback 
    // is the XMLHttpRequest object's responseXML property 

    // if the ajaxForm method was passed an Options Object with the dataType 
    // property set to 'json' then the first argument to the success callback 
    // is the json data object returned by the server 

   // alert('status: ' + statusText + '\n\nresponseText: \n' + responseText +
     //   '\n\nThe output div should have already been updated with the responseText.');
} 
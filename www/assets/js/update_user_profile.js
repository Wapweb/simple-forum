// prepare the form when the DOM is ready 
$(document).ready(function() {
    var options = {
        //$('#view_settings').attr('action')+
        dataType:  'json',
        url:       $('#view_settings').attr('action')+'/json',
        success:   processJson,
        beforeSubmit:  showRequest,  // pre-submit callback
        error:function (){
            $('#button_view').html("Сохранить");
            $('#button_view').attr('disabled', false);
            $("#output_view").html(
                '<div class="alert alert-danger alert-danger">'+
                '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                'Системная ошибка. Обратитесь к администратору'+
                '</div>'
            );
        }
    };

    // bind form using 'ajaxForm'
    $('#view_settings').ajaxForm(options);

    var options_profile = {
        //$('#view_settings').attr('action')+
        dataType:  'json',
        url:       $('#profile_settings').attr('action')+'/json',
        success:   processJson_profile,
        beforeSubmit:  showRequest_profile,  // pre-submit callback
        error:function (){
            $('#button_profile').html("Сохранить");
            $('#button_profile').attr('disabled', false);
            $("#output_profile").html(
                '<div class="alert alert-danger alert-danger">'+
                    '<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>'+
                    'Системная ошибка. Обратитесь к администратору'+
                    '</div>'
            );
        }
    };

    // bind form using 'ajaxForm'
    $('#profile_settings').ajaxForm(options_profile);
});

function processJson_profile(data) {
    $('#button_profile').html("Сохранить");
    $('#button_profile').attr('disabled', false);
    $("#output_profile").html(data.out);
}

// pre-submit callback
function showRequest_profile(formData, jqForm, options) {
    $('#button_profile').append(" <img src='/assets/images/update.gif' alt='update'>");
    $('#button_profile').attr('disabled', true);
    return true;
}

function processJson(data) {
    $('#button_view').html("Сохранить");
    $('#button_view').attr('disabled', false);
    $("#output_view").html(data.out);
}

// pre-submit callback
function showRequest(formData, jqForm, options) {
    $('#button_view').append(" <img src='/assets/images/update.gif' alt='update'>");
    $('#button_view').attr('disabled', true);
    return true;
}
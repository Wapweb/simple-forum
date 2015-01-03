// prepare the form when the DOM is ready 
$(document).ready(function() {
    var filesData = [];
    var options = {
        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback
        beforeSend: function() {
        },
        uploadProgress: function() {
        },
        complete: function() {
        },
        data: {json:"true"},
        // other available options: 
        //url:       url         // override for form's 'action' attribute 
        //type:      type        // 'get' or 'post', override for form's 'method' attribute
        dataType:  "json"        // 'xml', 'script', or 'json' (expected server response type)
        //clearForm: true        // clear all form fields after successful submit 
        //resetForm: true        // reset the form after successful submit 

        // $.ajax options can be used here too, for example: 
        //timeout:   3000 
    };

    // bind form using 'ajaxForm' 
    $('#msgForm').ajaxForm(options);

    // pre-submit callback
    function showRequest(formData, jqForm, options) {
        var form = jqForm[0];
        var message_text = form.message_text.value;
        if(message_text == "") {
            $("#output").html("<div class='alert alert-danger'>Вы не ввели текст сообщения!</div>");
            return false;
        }
        $.getScript( "/assets/js/utility_get_files.js", function( data, textStatus, jqxhr ) {
            filesData = getFiles();
        });
        return true;
    }

// post-submit callback
    function showResponse(responseText, statusText, xhr, $form)  {
        if(responseText.error_msg != "") {
            alert(responseText.error_msg);
        } else {

            var filesCount = filesData.length;
            var fileMsg = "";
            if(filesCount > 0) {
                fileMsg = "(Прикреплено "+filesCount+" файлов)";
            }
            var message_id = responseText.message_id;
            var topic_page = responseText.topic_page;
            var topic_name = responseText.topic_name;
            var request = $.ajax({
                url: "/topic/attach_files/"+message_id+"/"+topic_page+"/"+topic_name,
                type: "POST",
                data: {"arr" : filesData},
                dataType: "json"


            });
            request.fail(function() {
                alert("Ошибка прикрепление файлов! Обратитесь к администратору!");
            });
            request.done(function(data) {
                $("#output").html("<div class='alert alert-success'>Сообщение успешно добавлено! "+fileMsg+"</div>");
                setTimeout(function() {
                    window.location.href = data.redirect_url;
                },1000);
            });
        }
    }
});

// prepare the form when the DOM is ready 
$(document).ready(function() {

    var fileInput = $("input#files");
    var files = ($("input#files"))[0].files;

    var bar = $('.bar');
    var percent = $('.percent');
    var progress = $('.progress');

    var outDownload = $("#fileDownload");
    var out = $("#fileOut");

    $("#fileOut").on("change", function() {
       alert($(this).html());
    });

    fileInput.on("change", function() {
        $('#fileForm').trigger('submit');
    });

    var options = {

        beforeSubmit:  showRequest,  // pre-submit callback 
        success:       showResponse,  // post-submit callback

        uploadProgress: function(event, position, total, percentComplete) {
            var percentVal = percentComplete + '%';
            bar.width(percentVal);
            percent.html(percentVal);
        },

        complete: showComplete
    };

    // bind to the form's submit event 
    $('#fileForm').submit(function() {
        // inside event callbacks 'this' is the DOM element so we first 
        // wrap it in a jQuery object and then invoke ajaxSubmit 
        $(this).ajaxSubmit(options);



        // !!! Important !!! 
        // always return false to prevent standard browser submit and page navigation 
        return false;
    });






    // pre-submit callback
    function showRequest(formData, jqForm, options) {
        //out.empty();
       // console.log(out.val());
        //console.log(out.html());
        var old = out.html();
        out.html(old);
        outDownload.append("<b>Идет загрузка файлов</b> ");
        $.each(files, function(key,file) {
            outDownload.append("<br>"+file.name);
            console.log(file.size);
        });

        var percentVal = '0%';
        bar.width(percentVal);
        percent.html(percentVal);
        progress.show();
    }

// post-submit callback
    function showResponse(responseText, statusText, xhr, $form)  {
        var percentVal = '100%';
        bar.width(percentVal)
        percent.html(percentVal);
    }

    function showComplete(xhr) {
        //console.log(out.html());
        outDownload.empty();
        out.append(xhr.responseText);
        /*$.each(files, function(key,file) {
            out.append("<div class='f'>"+file.name+" x</div>");
            console.log(file.size);
        });*/
        progress.hide();
        $(".file-input-name").remove();

    }
});

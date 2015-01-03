$(document).ready(function() {
   //var file_delete = $("a.delete_link");
    //$("#fileOut").on("click",function(e) {
        //e.preventDefault();
        //alert( $("a.delete_link").attr("href"));
    //delegate for dynamic content

    var files = $(".file");
    $("#fileOut").on("click",".delete_link", function(e) {
        e.preventDefault();
        var file_id = $(this).attr("id");
        $(this).parent().remove();

        var request = $.ajax({
            url: "/file/delete/"+file_id,
            type: "POST",
            data: {file_id : file_id},
            dataType: "json"

        });
        request.fail(function() {
            alert("Ошибка удаления файла! Обратитесь к администратору!");
        });
        request.done(function(data) {
            if(data.success == true) {
                //alert("Файл успешно удален!");
            }
        });


        //alert( $(this).attr("id"));
        });
    //});
});
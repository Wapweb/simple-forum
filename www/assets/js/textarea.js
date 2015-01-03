// prepare the form when the DOM is ready 
$(document).ready(function() {

   /* $('#spinner')
        .hide()  // hide it initially
        .ajaxStart(function() {
            $(this).show();
        })
        .ajaxStop(function() {
            $(this).hide();
        })
    ;*/

    var wbbOpt = {
        buttons: "bold,italic,underline,|img,link,smilebox,|,code,quote",
        autoresize: false

    }
    $('#textarea_msg').wysibb(wbbOpt);
    //$("#textarea_msg").htmlcode('testsd');
   /* $("#msg_answer").bind('click', function() {
        var author = window.location.hash.substr(1);
        $("#textarea_msg").htmlcode(author+',<br>');
    });*/



    $('.msg_answer').click(function(e){
        e.preventDefault();
        var targetUrl =  $(this).attr('href');
        //alert($(this).attr('id'));
        $.ajax({
            url: targetUrl+"/json/",
            type: "GET",
            beforeSend: function() {
                $('#spinner').show();
            },
            complete: function(){
                $('#spinner').hide();
            },
           // data: { type: "json" },
            dataType: "json",
            success:function(msg){
                //alert(msg.test);
                $('html, body').animate({
                    scrollTop: $("#msg").offset().top
                }, 500);
                $("#empty_msg").hide();
                $("#textarea_msg").htmlcode('');
                $("#textarea_msg").htmlcode(msg.user_login+' , ');
                return false;
            },
            error:function (){
                alert("Системная ошибка. Обратитесь к администрации форума!");
            }
        });
    });

    $('.msg_quote').click(function(e){
        e.preventDefault();
        var targetUrl = $(this).attr('href');
        //alert($(this).attr('id'));
        //alert(targetUrl);
        $.ajax({
            url: targetUrl+"/json/",
            type: "GET",
            dataType: "json",
            beforeSend: function() {
                $('#spinner').show();
            },
            complete: function(){
                $('#spinner').hide();
            },
            success:function(msg){
                //alert(msg.test);
                $('html, body').animate({
                    scrollTop: $("#msg").offset().top
                }, 500);
                $("#empty_msg").hide();
                $("#textarea_msg").htmlcode('');
                $("#textarea_msg").htmlcode(msg.out);
                return false;
            },
            error:function (){
                alert("Системная ошибка. Обратитесь к администрации форума!");
            }
        });
    });
   // $( "#msg_quote" ).click(function() {
        //var type = window.location.hash.substr(1);
       // alert(type);
    //});
});
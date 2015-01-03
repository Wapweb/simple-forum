$(document).ready(function() {
   var recipientInput = $("input#recipient");
   var titleInput = $("input#title");
   var output = $("#output");

   // setTimeout(showSearch(),1000);
    //setTimeout(hideSearch(),1000);

    /*$("#output").on("click",".users li", function(e) {
        e.preventDefault();
        alert("catch");

        //alert( $(this).attr("id"));
    });*/
    selectedLogin();

    recipientInput.on("keyup",function() {
        var searchString = $(this).val();
        //alert(searchString);
        if(searchString != "") {
            $.ajax({
                type: "POST",
                url: "/mail/search_recipient",
                data: {
                    login: searchString
                },
                cache: false,
                success: function(html) {
                    output.html(html);
                }
            });
        } else {
            output.html("");
        }
    });


    function controlSearch() {
        recipientInput.focusout(function() {
            setTimeout(function() {
                output.hide()
            },300);
        });
        recipientInput.focusin(function() {
            setTimeout(function() {
                output.show()
            },300);
        });
    }

    function selectedLogin() {
        output.on("click","a.users",function(e) {
            e.preventDefault();
            //alert($(this).attr("id"));
            recipientInput.val($(this).attr("id"));
            output.hide();
            titleInput.focus();
        });
        controlSearch();
    }
});
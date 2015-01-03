$(document).ready(function() {
    //подстраиваем кнопку "наверх" под размер окна
    function modern_upbutton_resize(){
        var ourwidth_screen = $(window).width(); // взяли ширину окна
        if(ourwidth_screen >= 1007) { // здесь и ниже число пикселей будет вашим, так как зависит от ширины сайта
            //alert(">=1007");
            console.log('>=1007');
            $('.modern-upbutton').css('width',(ourwidth_screen-860)/8); // окно большое, подогнали под размер
            smallscreen = false;
            if ($('.modern-upbutton').hasClass('its-a-mobile-modern-upbutton')) {
                $('.modern-upbutton').removeClass('its-a-mobile-modern-upbutton');
            }
        } else if (ourwidth_screen >= 800) {
            //alert(">=955");
            console.log('>=955')
            $('.modern-upbutton').addClass('its-a-mobile-modern-upbutton'); // маленькое окно или планшет
            smallscreen = false;
        } else {
            // alert("<955");
            console.log('<955')
            smallscreen = true; // окно настолько мало, что места для кнопки просто нет, прячем её
            $('.modern-upbutton').hide();
        }
    }
//при изменении юзером размеров окна подстраиваем кнопку под новый масштаб
    $(window).resize(function(){
        modern_upbutton_resize();
    });
//обработка клика по кнопке наверх - прокрутка вверх
    function modern_upbutton_click_scrollup() {
        $('.modern-upbutton').attr('data-pos',$(window).scrollTop()); // запоминаем место, от которого проматываем наверх
        window.scrollTo("1","1"); // прокрутка к началу
        $('.modern-upbutton').hide();
        return false;
    }

//управление показом и скрытием стрелки
    $(window).scroll(function() {
        if(smallscreen == false && window.modern_upbutton_was_killed != true) {
            if($(window).scrollTop() >= 300) {  // если прокрутили уже 300 пикселей...
                $('.modern-upbutton').attr('data-scroll', 'up');
                $('.modern-upbutton').fadeIn(300); // показываем кнопку
            } else if ($('.modern-upbutton').attr('data-pos') == "0") {  // если верх страницы...
                if ($('.modern-upbutton').attr('data-scroll') == 'up') {
                    $('.modern-upbutton').fadeOut(300); // скрываем её
                }
            }
        }
    });

    //подгоняем её под окно
    modern_upbutton_resize();
    //вешаем следилку на событие "клик по кнопке"
    $('.modern-upbutton').bind("click touch ontouchstart", function(e){
        if ($(e.target).closest(".modern-upbutton-disable").length) return;
        if ($(this).attr('data-scroll') == 'up') {
            modern_upbutton_click_scrollup();
        }

        e.stopPropagation();
    });
});
$(document).ready(function () {
    $('.inputADMoptional').find('input[type=text]').attr('placeholder', 'Opcional');

    $('.toolbar .left button,.toolbar .right button').on('click', function () {
        $($(this).data('nav')).toggleClass('nav-lateral-show');
       
    });
    
    $('.nav-lateral ul li a').on('click', function (e) {
        $(".submenu").removeClass("in");
        if ($(this).next(".submenu").length) {
            $(this).next(".submenu").addClass("in");
            e.preventDefault();
        }
        
        
    });
    $(".content .right .details .title button").on("click",function(){
        $('.details').removeClass('show');
    })
    $(".content .right .details .title").on("click", function () {
        var width = document.body.clientWidth;
        if (width < 1200) {
            //$(".content .right .details .body").toggle();
            if ($(".content .right .details .body").hasClass("hide-body")) {
                $(".content .right .details .body").removeClass("hide-body");
            } else {
                $(".content .right .details .body").addClass("hide-body");
            }

        }
    });

    $(".Tabs-navs a").click(function () {
        var panelProductividad = $(".left > .panel.resumen");
        if ($(this).attr("href") == "#productividad") {
            panelProductividad.removeClass('hide');
        } else {
            panelProductividad.addClass('hide');
        }
    });
    
});

$(document).mouseup(function (e) {
    var container = $(".nav-lateral-show");
    var width = document.body.clientWidth;
    if (container != undefined) {
        if (!container.is(e.target) && container.has(e.target).length === 0) // ... nor a descendant of the container
        {
            container.removeClass('nav-lateral-show');

        }
    }
    
});

$(window).resize(function () {
    var w = document.body.clientWidth;
    if ($("#resumen") != undefined) {
        if (w > 1024) {
            $("#resumen").removeClass("panel-fixed");
        } else if (w <= 1024 && w > 992) {
            $("#resumen").removeClass("panel-fixed");
        } else if (w > 768 && w <= 992) {

        } else if (w <= 768) {

        }
    }
    
});

$(window).scroll(function () {
    var w = document.body.clientWidth, resumen_top = 470, banner_height = (document.getElementsByClassName("banner")[0] != undefined) ? document.getElementsByClassName("banner")[0].clientHeight : 0;
    if (w > 992) {
        
        if ($(".resumen") != undefined) {
            if ($(this).scrollTop() > resumen_top) {
                $(".resumen").addClass("fixed-panel");
            } else {
                $(".resumen").removeClass("fixed-panel");
            }
        }
    }
    if (w > 992) {
        if ($(this).scrollTop() > banner_height) {
            $("#title-toolbar").html($("h2:first").html());
        } else {
            $("#title-toolbar").html("");
        }
    }
});

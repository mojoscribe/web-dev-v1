// $(document).ready(function(e){var t=false;if(window.location.href==baseUrl){$("#home").addClass("nav-active-page")}else if(window.location.href==baseUrl+"dashboard"){$("#home").addClass("nav-active-page")}else if(window.location.href==baseUrl+"mojoPicks"){$("#featured").addClass("nav-active-page")}else if(window.location.href==baseUrl+"categories"){$("#categories").addClass("nav-active-page")}else if(window.location.href==baseUrl+"poll"){$("#polls").addClass("nav-active-page")}else if(window.location.href==baseUrl+"page/about"){$("#aboutUs").addClass("nav-active-page")}else if(window.location.href==baseUrl+"page/contact"){$("#contactUs").addClass("nav-active-page")}else if(window.location.href==baseUrl+"page/anonymous"){$("#anonymous").addClass("nav-active-page")}else if(window.location.href==baseUrl+"recent"){$("#recent").addClass("nav-active-page")}var n=false;$("#categories").mouseenter(function(){n=true;$(".categories-menu").slideDown("fast","swing")});$("#categories").mouseleave(function(){n=false;setTimeout(function(){if(!t){$(".categories-menu").slideUp("fast","swing")}},100)});$(".categories-menu").mouseenter(function(){t=true});$(".categories-menu").mouseleave(function(){t=false;setTimeout(function(){if(!n){$(".categories-menu").slideUp("fast","swing")}},50)});$(document).mouseup(function(e){var n=$("#categories");var r=$(".categories-menu");if(!n.is(e.target)&&r.has(e.target).length===0){$(".categories-menu").slideUp("fast","swing");t=false}})})
$(document).ready(function(e) {
    var t = false;
    if (window.location.href == baseUrl) {
        $("#home").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "dashboard") {
        $("#home").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "mojoPicks") {
        $("#featured").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "categories") {
        $("#categories").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "poll") {
        $("#polls").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "page/contact") {
        $("#contactUs").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "page/anonymous") {
        $("#anonymous").addClass("nav-active-page")
    } else if (window.location.href == baseUrl + "recent") {
        $("#recent").addClass("nav-active-page")
    }
    var n = false;
    $("#categories").mouseenter(function() {
        n = true;
        $(".categories-menu").slideDown("fast", "swing")
    });
    $("#categories").mouseleave(function() {
        n = false;
        setTimeout(function() {
            if (!t) {
                $(".categories-menu").slideUp("fast", "swing")
            }
        }, 100)
    });
    $(".categories-menu").mouseenter(function() {
        t = true
    });
    $(".categories-menu").mouseleave(function() {
        t = false;
        setTimeout(function() {
            if (!n) {
                $(".categories-menu").slideUp("fast", "swing")
            }
        }, 50)
    });
    $(document).mouseup(function(e) {
        var n = $("#categories");
        var r = $(".categories-menu");
        if (!n.is(e.target) && r.has(e.target).length === 0) {
            $(".categories-menu").slideUp("fast", "swing");
            t = false
        }
    })
})
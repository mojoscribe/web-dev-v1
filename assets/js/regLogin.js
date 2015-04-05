// $(document).ready(function(e){var t=false;var n=false;$("#register").click(function(e){console.log("register");console.log(t);console.log(n);if(t==true){$(".register-menu").slideUp("fast","swing");t=false}else{$(".register-menu").slideDown("fast","swing");$(".login-menu").hide();t=true;n=false}});$("#login").click(function(e){console.log("Login");console.log(t);console.log(n);if(n==true){$(".login-menu").slideUp("fast","swing");n=false}else{$(".login-menu").slideDown("fast","swing");$(".register-menu").hide();n=true;t=false}});$(document).mouseup(function(e){var r=$("#login");var i=$("#register");var s=$(".register-menu");var o=$(".login-menu");if(!(r.is(e.target)||i.is(e.target))&&(s.has(e.target).length||o.has(e.target).length)===0){$(".register-menu").slideUp("fast","swing");$(".login-menu").slideUp("fast","swing");t=false;n=false}})})
$(document).ready(function(e) {
    var t = false;
    var n = false;
    $("#register").click(function(e) {
        console.log("register");
        console.log(t);
        console.log(n);
        if (t == true) {
            $(".register-menu").slideUp("fast", "swing");
            t = false
        } else {
            $(".register-menu").slideDown("fast", "swing");
            $(".login-menu").hide();
            t = true;
            n = false
        }
    });
    $("#login").click(function(e) {
        console.log("Login");
        console.log(t);
        console.log(n);
        if (n == true) {
            $(".login-menu").slideUp("fast", "swing");
            n = false
        } else {
            $(".login-menu").slideDown("fast", "swing");
            $(".register-menu").hide();
            n = true;
            t = false
        }
    });
    $(document).mouseup(function(e) {
        var r = $("#login");
        var i = $("#register");
        var s = $(".register-menu");
        var o = $(".login-menu");
        if (!(r.is(e.target) || i.is(e.target)) && (s.has(e.target).length || o.has(e.target).length) === 0) {
            $(".register-menu").slideUp("fast", "swing");
            $(".login-menu").slideUp("fast", "swing");
            t = false;
            n = false
        }
    })
})
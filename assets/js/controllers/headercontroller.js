// var HeaderCtrl=["$scope","$http",function(e,t){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.registerValidated=false;e.login={userName:"",password:""};e.showNotifCount=false;e.trending=[];e.userNotifications=[];e.categories=[];e.register={userName:"",password:"",confirmPassword:"",email:""};e.trendingClicked=false;e.message={message:"Please wait while we register you"};e.getCategories=function(){var n=baseUrl+"mainPage/getCategories";var r="";t.post(n,r).success(function(t){e.categories=t.data})};e.loginAuthenticate=function(){var n=baseUrl+"login/authenticate";var r=e.login;t.post(n,r).success(function(e){if(e.success==true){window.location=window.location.href;}else if(e.success==false){if(e.error.msg=="User Banned"){$("#ban-modal").modal("show")}else if(e.error.msg=="User does not exist"){$("#login-modal").modal("show")}else{}}})};e.registerAuthenticate=function(){if(e.registerValidated){$("#waiting-modal").modal("show");var n=baseUrl+"register";var r=e.register;t.post(n,r).success(function(t){if(t.success==true){window.location=baseUrl+"firstTime?userName="+e.register.userName+"&email="+e.register.email}else if(t.success==false){if(t.error.msg=="User Banned"){$("#regUserName").prop("title","User Banned. Contact Admin");$("#regUserName").tooltip("show")}}})}};e.checkUserName=function(){var n=baseUrl+"register/checkUserName";var r=e.register.userName;t.post(n,r).success(function(t){if(t.success==true){$("#regUserName").addClass("available");e.registerValidated=true}else if(t.success==false){if(t.error.msg=="UserName already exists"){$("#regUserName").addClass("error-class");$("#regUserName").prop("title","UserName already exists. Try something else");$("#regUserName").tooltip("show")}else if(t.error.msg=="The User Name you are trying to enter is Invalid"){$("#regUserName").addClass("error-class");$("#regUserName").prop("title","The User Name you are trying to enter is Invalid");$("#regUserName").tooltip("show")}e.registerValidated=false}})};e.checkEmail=function(){var n=baseUrl+"register/checkEmail";var r=e.register.email;t.post(n,r).success(function(t){if(t.success==true){$("#regEmail").addClass("available");e.registerValidated=true}else if(t.success==false){if(t.error.msg=="Email Address already exists"){$("#regEmail").addClass("error-class");$("#regEmail").prop("title","Email is already registered. Please use another Email Address");$("#regEmail").tooltip("show")}e.registerValidated=false}})};e.checkPasswordLength=function(){if(e.register.password.length<8){$("#password").addClass("error-class");$("#password").prop("title","Passwords must be of at least 8 characters");$("#password").tooltip("show");e.registerValidated=false}else{$("#password").removeClass("error-class");$("#password").tooltip("hide")}};e.checkPassword=function(){if(e.register.password!=e.register.confirmPassword){$("#confirmPassword").addClass("error-class");$("#confirmPassword").prop("title","Passwords must be the same");$("#confirmPassword").tooltip("show");e.registerValidated=false}else{e.registerValidated=true;$("#confirmPassword").removeClass("error-class");$("#confirmPassword").tooltip("hide")}};e.notificationsClicked=false;e.notifications=function(){if(e.notificationsClicked==true){$("#notifications-menu").slideUp("fast","swing");e.notificationsClicked=false}else{$("#notifications-menu").slideDown("fast","swing");e.notificationsClicked=true}};e.notifRead=function(){var n=baseUrl+"makeRead";t.get(n).success(function(t){if(t.success==true){e.showNotifCount=false}})};e.getNotifications=function(){var n=baseUrl+"getNotifications";var r="";t.post(n,r).success(function(t){if(t.success==true){e.userNotifications=t.data.data;e.notifLength=t.data.count;if(e.notifLength>0){e.showNotifCount=true}}})};e.getNotifications();$(document).mouseup(function(t){var n=$(".notifications");var r=$("#notifications-menu");if(!(n.is(t.target)&&(n.has(t.target).length||r.has(t.target).length)===0)){$("#notifications-menu").slideUp("fast","swing");e.notificationsClicked=false}});e.menu=function(){if(e.trendingClicked==true){e.trendingClicked=false}else if(e.trendingClicked==false){e.trendingClicked=true}};$(document).mouseup(function(t){if(!($(".trending-menu").is(t.target)||$(".trendTriangle").is(t.target))&&($(".trending-menu").is(t.target).length||$(".trendTriangle").is(t.target).length)===0){e.trendingClicked=false}});e.trendingFocus=function(){$("html,body").animate({scrollTop:$("#trendingNewsContainer").offset().top-62},1e3,function(){$("#trendingNewsContainer").focus()})};e.breakingFocus=function(){$("html,body").animate({scrollTop:$("#breakingNewsContainer").offset().top-61},1e3,function(){$("#breakingNewsContainer").focus()})};e.mojoFocus=function(){$("html,body").animate({scrollTop:$("#bottom-news-slide").offset().top-108},1e3,function(){$("#bottom-news-slide").focus()})};e.trendingHashtags=function(){var n=baseUrl+"mainPage/getTrendingHashtagsList";var r="";t.post(n,r).success(function(t){if(t.success==true){e.trending=t.data}else{}})};e.trendingHashtags();e.getCategories()}]
var HeaderCtrl = ["$scope", "$http", function(e, t) {
    t.defaults.headers.common["XSRF_TOKEN"] = $("#csrf").val();
    e.registerValidated = false;
    e.login = {
        userName: "",
        password: ""
    };
    e.showNotifCount = false;
    e.trending = [];
    e.userNotifications = [];
    e.categories = [];
    e.register = {
        userName: "",
        password: "",
        confirmPassword: "",
        email: ""
    };
    e.trendingClicked = false;
    e.message = {
        message: "Please wait while we register you"
    };
    e.getCategories = function() {
        var n = baseUrl + "mainPage/getCategories";
        var r = "";
        t.post(n, r).success(function(t) {
            e.categories = t.data
        })
    };
    e.loginAuthenticate = function() {
        var n = baseUrl + "login/authenticate";
        var r = e.login;
        $(".register-menu").slideUp("fast", "swing");
        $(".login-menu").slideUp("fast", "swing");
 
        t.post(n, r).success(function(e) {
            if (e.success == true) {
                window.location = window.location.href;
            } else if (e.success == false) {
                if (e.error.msg == "User Banned") {
                    $("#ban-modal").modal("show")
                } else if (e.error.msg == "User does not exist") {
                    $("#login-modal").modal("show")
                } else {
                    $("#login-modal").modal("show")
                }
            }
        })
    };
    e.registerAuthenticate = function() {
        if (e.registerValidated) {
            $("#waiting-modal").modal("show");
            var n = baseUrl + "register";
            var r = e.register;
            t.post(n, r).success(function(t) {
                if (t.success == true) {
                    window.location = baseUrl + "firstTime?userName=" + e.register.userName + "&email=" + e.register.email
                } else if (t.success == false) {
                    if (t.error.msg == "User Banned") {
                        $("#regUserName").prop("title", "User Banned. Contact Admin");
                        $("#regUserName").tooltip("show")
                    }
                }
            })
        }
    };
    e.checkUserName = function() {
    	if (e.register.userName.length < 6) {
        	$("#regUserName").addClass("error-class");
	       	$("#regUserName").prop("title", "The Reporter Handle has to be at least 6 characters long");
            $("#regUserName").tooltip("show")
            return false;
	    } else if (e.profileInformation.reporterHandle.indexOf(" ") >= 0) {
	        $("#regUserName").addClass("error-class");
	        $("#regUserName").prop("title", "The Reporter Handle you are trying to enter is Invalid");
            $("#regUserName").tooltip("show")
            return false;
	    } else{
	    	$("#regUserName").removeClass("error-class");
            $("#regUserName").tooltip("hide");
	       
        }
    	
        var n = baseUrl + "register/checkUserName";
        var data = {
            id:0,
            reporter:e.register.userName
        }
        var r = data;
        t.post(n, r).success(function(t) {
            if (t.success == true) {
                $("#regUserName").addClass("available");
                e.registerValidated = true
            } else if (t.success == false) {
                if (t.error.msg == "UserName already exists") {
                    $("#regUserName").addClass("error-class");
                    $("#regUserName").prop("title", "Reporter Handle already exists. Try something else");
                    $("#regUserName").tooltip("show")
                } else if (t.error.msg == "The User Name you are trying to enter is Invalid") {
                    $("#regUserName").addClass("error-class");
                    $("#regUserName").prop("title", "The Reporter Handle you are trying to enter is Invalid");
                    $("#regUserName").tooltip("show")
                }else if ($("#regUserName").val().length < 6) {
	            	$("#regUserName").addClass("error-class");
			       	$("#regUserName").prop("title", "The Reporter Handle has to be at least 6 characters long");
                    $("#regUserName").tooltip("show")
			    } else if (e.profileInformation.reporterHandle.indexOf(" ") >= 0) {
			        $("#regUserName").addClass("error-class");
			        $("#regUserName").prop("title", "The Reporter Handle you are trying to enter is Invalid");
                    $("#regUserName").tooltip("show")
			    } else{
                    $("#regUserName").removeClass("error-class");
                    $("#regUserName").tooltip("hide");
                }
                e.registerValidated = false
            }
        })
    };
    e.checkEmail = function() {
        var n = baseUrl + "register/checkEmail";
        var r = e.register.email;
        t.post(n, r).success(function(t) {
            if (t.success == true) {
                $("#regEmail").addClass("available");
                e.registerValidated = true
            } else if (t.success == false) {
                if (t.error.msg == "Email Address already exists") {
                    $("#regEmail").addClass("error-class");
                    $("#regEmail").prop("title", "Email is already registered. Please use another Email Address");
                    $("#regEmail").tooltip("show")
                }
                e.registerValidated = false
            }
        })
    };
    e.checkPasswordLength = function() {
        if (e.register.password.length < 8) {
            $("#password").addClass("error-class");
            $("#password").prop("title", "Passwords must be of at least 8 characters");
            $("#password").tooltip("show");
            e.registerValidated = false
        } else {
            $("#password").removeClass("error-class");
            $("#password").tooltip("hide")
        }
    };
    e.checkPassword = function() {
        if (e.register.password != e.register.confirmPassword) {
            $("#confirmPassword").addClass("error-class");
            $("#confirmPassword").prop("title", "Passwords must be the same");
            $("#confirmPassword").tooltip("show");
            e.registerValidated = false
        } else {
            e.registerValidated = true;
            $("#confirmPassword").removeClass("error-class");
            $("#confirmPassword").tooltip("hide")
        }
    };
    e.notificationsClicked = false;
    e.notifications = function() {
        if (e.notificationsClicked == true) {
            $("#notifications-menu").slideUp("fast", "swing");
            e.notificationsClicked = false
        } else {
            $("#notifications-menu").slideDown("fast", "swing");
            e.notificationsClicked = true
        }
    };
    e.notifRead = function() {
        var n = baseUrl + "makeRead";
        t.get(n).success(function(t) {
            if (t.success == true) {
                e.showNotifCount = false
            }
        })
    };
    e.getNotifications = function() {
        var n = baseUrl + "getNotifications";
        var r = "";
        t.post(n, r).success(function(t) {
            if (t.success == true) {
                e.userNotifications = t.data.data;
                e.notifLength = t.data.count;
                if (e.notifLength > 0) {
                    e.showNotifCount = true
                }
            }
        })
    };
    e.getNotifications();
    $(document).mouseup(function(t) {
        var n = $(".notifications");
        var r = $("#notifications-menu");
        if (!(n.is(t.target) && (n.has(t.target).length || r.has(t.target).length) === 0)) {
            $("#notifications-menu").slideUp("fast", "swing");
            e.notificationsClicked = false
        }
    });

    // $(document).load(function(){
    //     $('.actual-trending-menu').mouseover(function(){
    //         if (e.trendingClicked == true) {
    //             e.trendingClicked = false
    //         } else if (e.trendingClicked == false) {
    //             e.trendingClicked = true
    //         }
    //     });
    // });

    e.menu = function() {
        if (e.trendingClicked == true) {
            e.trendingClicked = false
        } else if (e.trendingClicked == false) {
            e.trendingClicked = true
        }
    };
    $(document).mouseup(function(t) {
        if (!($(".trending-menu").is(t.target) || $(".trendTriangle").is(t.target)) && ($(".trending-menu").is(t.target).length || $(".trendTriangle").is(t.target).length) === 0) {
            e.trendingClicked = false
        }
    });
    e.trendingFocus = function() {
        $("html,body").animate({
            scrollTop: $("#trendingNewsContainer").offset().top - 62
        }, 1e3, function() {
            $("#trendingNewsContainer").focus()
        })
    };
    e.breakingFocus = function() {
        $("html,body").animate({
            scrollTop: $("#breakingNewsContainer").offset().top - 61
        }, 1e3, function() {
            $("#breakingNewsContainer").focus()
        })
    };
    e.mojoFocus = function() {
        $("html,body").animate({
            scrollTop: $("#bottom-news-slide").offset().top - 108
        }, 1e3, function() {
            $("#bottom-news-slide").focus()
        })
    };
    e.trendingHashtags = function() {
        var n = baseUrl + "mainPage/getTrendingHashtagsList";
        var r = "";
        t.post(n, r).success(function(t) {
            if (t.success == true) {
                e.trending = t.data
            } else {}
        })
    };
    e.trendingHashtags();
    e.getCategories()
}]
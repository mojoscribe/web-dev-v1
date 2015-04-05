// var SettingsPageCtrl=["$scope","$http",function(e,t){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.profileInformation={id:null,reporterHandle:"",firstName:null,lastName:null,about:null,country:null,city:null,gender:null,contactNo:null};e.rhEmpty=false;e.rhDisallowed=false;e.insufLength=false;e.userNameAvailable=false;e.selectBox="";e.selectChange=function(){};e.genderMale=function(){e.profileInformation.gender="male"};e.genderFemale=function(){e.profileInformation.gender="female"};e.pictureIcon=function(){$("#upload-profilePicture").trigger("click")};e.pictureHover=false;e.profilePictureHover=function(){if(e.pictureHover==false){e.pictureHover=true}else{e.pictureHover=false}};e.profilePicChanged=function(t){e.files=t.files;var n=document.getElementById("upload-profilePicture").files;for(var r=0;r<n.length;r++){if(n[r].type.match("image.*")){var i=new FileReader;i.readAsDataURL(n[r]);i.onloadend=function(e){var t=document.getElementById("profile-picture-upload");t.src=e.target.result;$("#profile-picture-upload").css("opacity","1.0")}}else{alert("File Type you are uploading is not allowed. If you want to upload video please click on Video Icon")}}};e.getUserInfo=function(){var n=$("#userId").val();var r=baseUrl+"settings/getUser?id="+n;t.get(r).success(function(t){e.profileInformation.id=t.data.id;e.profileInformation.reporterHandle=t.data.userName})};e.replacer=function(e,t){return t};e.checkUserName=function(){if($("#reporterHandle").val()==""){$("#reporterHandle").addClass("error-class");e.rhEmpty=true;e.rhDisallowed=false;e.insufLength=false;return false}else if($("#reporterHandle").val().length<6){$("#reporterHandle").addClass("error-class");e.insufLength=true;e.rhEmpty=false;e.rhDisallowed=false;return false}else if(e.profileInformation.reporterHandle.indexOf(" ")>=0){$("#reporterHandle").addClass("error-class");e.rhDisallowed=true;e.rhEmpty=false;e.insufLength=false;return false}else{$("#reporterHandle").removeClass("error-class")}var n=baseUrl+"register/checkUserName";var r=e.profileInformation.reporterHandle;t.post(n,r).success(function(t){if(t.success==true){$("#reporterHandle").addClass("available");e.userNameAvailable=true;e.rhDisallowed=false;e.rhEmpty=false;e.insufLength=false}else{$("#reporterHandle").removeClass("available");$("#reporterHandle").addClass("error-class");e.userNameAvailable=false;e.rhDisallowed=false;e.rhEmpty=false;e.insufLength=false}})};e.checkNumber=function(t){console.log(e.profileInformation.contactNo);if(t.keyCode==46||t.keyCode==8||t.keyCode==9||t.keyCode==27||t.keyCode==13||t.keyCode==65&&t.ctrlKey===true||t.keyCode>=35&&t.keyCode<=39){return}else{if(t.shiftKey||(t.keyCode<48||t.keyCode>57)&&(t.keyCode<96||t.keyCode>105)){t.preventDefault()}}if(e.profileInformation.contactNo.length>13){t.preventDefault();return false}else if(e.profileInformation.contactNo.length<10){$("#contactNo").addClass("error-class")}else{$("#contactNo").removeClass("error-class")}};e.submitInfo=function(){if(e.userNameAvailable==false){return false}var n=new FormData;if(null!=e.files){angular.forEach(e.files,function(e){n.append("file",e)})}if(null!=e.profileInformation){n.append("profile",JSON.stringify(e.profileInformation,e.replacer))}var r=baseUrl+"settings/saveProfile";var i=n;t.post(r,i,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(t){if(t.data.msg=="Information Saved"){window.location=baseUrl+"settings/preferences?id="+e.profileInformation.id}})};e.initCitySearch=function(){var t={types:["(cities)"],componentRestrictions:{country:"in"}};var n=document.getElementById("city");var r=new google.maps.places.Autocomplete(n,t);var i={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"long_name",postal_code:"short_name"};google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var s=t.geometry.location.lng();var o=t.address_components[0].types[0];if("locality"==o){e.profileInformation.city=t.address_components[0][i[o]]}else{}})};e.initCountrySearch=function(){var t={types:["(regions)"]};var n=document.getElementById("country");var r=new google.maps.places.Autocomplete(n,t);var i={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"long_name",postal_code:"short_name"};google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var s=t.geometry.location.lng();for(var o=0;o<t.address_components.length;o++){var u=t.address_components[o].types[0];if("country"==u){e.profileInformation.country=t.address_components[o][i[u]]}else{}}})};e.initCitySearch();e.initCountrySearch();e.getUserInfo();$(window).keydown(function(e){if(e.keyCode==13){e.preventDefault();return false}})}]
var SettingsPageCtrl = ["$scope", "$http", function(e, t) {
    t.defaults.headers.common["XSRF_TOKEN"] = $("#csrf").val();
    e.profileInformation = {
        id: null,
        reporterHandle: "",
        firstName: null,
        lastName: null,
        about: null,
        country: null,
        city: null,
        gender: null,
        contactNo: null
    };
    e.countryCode = '';
    e.rhEmpty = false;
    e.rhDisallowed = false;
    e.insufLength = false;
    e.userNameAvailable = '';
    e.selectBox = "";
    e.selectChange = function() {};
    e.genderMale = function() {
        e.profileInformation.gender = "male"
    };
    e.genderFemale = function() {
        e.profileInformation.gender = "female"
    };
    e.pictureIcon = function() {
        $("#upload-profilePicture").trigger("click")
    };
    e.pictureHover = false;
    e.profilePictureHover = function() {
        if (e.pictureHover == false) {
            e.pictureHover = true
        } else {
            e.pictureHover = false
        }
    };
    e.profilePicChanged = function(t) {
        e.files = t.files;
        var n = document.getElementById("upload-profilePicture").files;
        for (var r = 0; r < n.length; r++) {
            if (n[r].type.match("image.*")) {
                var i = new FileReader;
                i.readAsDataURL(n[r]);
                i.onloadend = function(e) {
                    var t = document.getElementById("profile-picture-upload");
                    t.src = e.target.result;
                    $("#profile-picture-upload").css("opacity", "1.0")
                }
            } else {
                alert("File Type you are uploading is not allowed. If you want to upload video please click on Video Icon")
            }
        }
    };
    e.getUserInfo = function() {
        var n = $("#userId").val();
        var r = baseUrl + "settings/getUser?id=" + n;
        t.get(r).success(function(t) {
            e.profileInformation.id = t.data.id;
            e.profileInformation.reporterHandle = t.data.userName

        })
    };
    e.replacer = function(e, t) {
        return t
    };
    e.checkUserName = function() {
        if ($("#reporterHandle").val() == "") {
            $("#reporterHandle").addClass("error-class");
            e.rhEmpty = true;
            e.rhDisallowed = false;
            e.insufLength = false;
            //e.userNameAvailable = false;
            return false;
        } else if ($("#reporterHandle").val().length < 6) {
            $("#reporterHandle").addClass("error-class");
            e.insufLength = true;
            e.rhEmpty = false;
            e.rhDisallowed = false;
            //e.userNameAvailable = false;
            return false;
        } else if (e.profileInformation.reporterHandle.indexOf(" ") >= 0) {
            $("#reporterHandle").addClass("error-class");
            e.rhDisallowed = true;
            e.rhEmpty = false;
            e.insufLength = false;
            //e.userNameAvailable = false;
            return false;
        } else {
            $("#reporterHandle").removeClass("error-class");
            var data = {
                id:e.profileInformation.id,
                reporter:e.profileInformation.reporterHandle
            }
            var n = baseUrl + "register/checkUserName";
            var r = data;
            t.post(n, r).success(function(t) {
                if (t.success == true) {
                    $("#reporterHandle").addClass("available");
                    e.userNameAvailable = true;
                    e.rhDisallowed = false;
                    e.rhEmpty = false;
                    e.insufLength = false
                } else {
                    $("#reporterHandle").removeClass("available");
                    $("#reporterHandle").addClass("error-class");
                    e.userNameAvailable = false;
                    e.rhDisallowed = false;
                    e.rhEmpty = false;
                    e.insufLength = false
                }
            })
        }
    };
    e.checkNumber = function(t) {
        console.log(e.profileInformation.contactNo);
        if (t.keyCode == 46 || t.keyCode == 8 || t.keyCode == 9 || t.keyCode == 27 || t.keyCode == 13 || t.keyCode == 65 && t.ctrlKey === true || t.keyCode >= 35 && t.keyCode <= 39) {
            return
        } else {
            if (t.shiftKey || (t.keyCode < 48 || t.keyCode > 57) && (t.keyCode < 96 || t.keyCode > 105)) {
                t.preventDefault()
            }
        }
        if (e.profileInformation.contactNo.length > 13) {
            t.preventDefault();
            return false
        } else if (e.profileInformation.contactNo.length < 10) {
            $("#contactNo").addClass("error-class")
        } else {
            $("#contactNo").removeClass("error-class")
        }
    };
    e.submitInfo = function() {
        if (e.userNameAvailable == false) {
            return false
        }
        var n = new FormData;
        if (null != e.files) {
            angular.forEach(e.files, function(e) {
                n.append("file", e)
            })
        }
        if (null != e.profileInformation) {
            n.append("profile", JSON.stringify(e.profileInformation, e.replacer))
        }
        var r = baseUrl + "settings/saveProfile";
        var i = n;
        t.post(r, i, {
            transformRequest: angular.identity,
            headers: {
                "Content-type": undefined
            }
        }).success(function(t) {
            if (t.data.msg == "Information Saved") {
                window.location = baseUrl + "settings/preferences?id=" + e.profileInformation.id
            }
        })
    };
    e.initCitySearch = function() {
        var t = {
            types: ["(cities)"],
            componentRestrictions: {
                country: "in"
            }
        };
        var n = document.getElementById("city");
        var r = new google.maps.places.Autocomplete(n, t);
        var i = {
            street_number: "short_name",
            route: "long_name",
            locality: "long_name",
            administrative_area_level_1: "short_name",
            country: "long_name",
            postal_code: "short_name"
        };
        google.maps.event.addListener(r, "place_changed", function() {
            var t = r.getPlace();
            var n = t.geometry.location.lat();
            var s = t.geometry.location.lng();
            var o = t.address_components[0].types[0];
            if ("locality" == o) {
                e.profileInformation.city = t.address_components[0][i[o]]
            } else {}
        })
    };
    e.initCountrySearch = function() {
        var t = {
            types: ["(regions)"]
        };
        var n = document.getElementById("country");
        var r = new google.maps.places.Autocomplete(n, t);
        var i = {
            street_number: "short_name",
            route: "long_name",
            locality: "long_name",
            administrative_area_level_1: "short_name",
            country: "long_name",
            postal_code: "short_name"
        };
        google.maps.event.addListener(r, "place_changed", function() {
            var t = r.getPlace();
            var n = t.geometry.location.lat();
            var s = t.geometry.location.lng();
            console.log(t);
            for (var o = 0; o < t.address_components.length; o++) {
                var u = t.address_components[o].types[0];
                if ("country" == u) {
                    e.profileInformation.country = t.address_components[o][i[u]]
                } else {}
            }
            e.initCitySearch();
        })
    };
    // e.initCitySearch();
    e.initCountrySearch();
    if(e.countryCode){
    	e.initCitySearch();
    }
    e.getUserInfo();
    // e.checkUserName();
    setTimeout(function(){
    	e.checkUserName();
    },200);
    $(window).keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false
        }
    })
}]

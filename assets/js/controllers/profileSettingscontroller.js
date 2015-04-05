// var ProfileSettingCtrl=["$scope","$http","$timeout",function(e,t,n){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.profileInformation={id:null,reporterHandle:null,firstName:null,lastName:null,about:"",country:null,city:null,gender:null,contactNo:null,profilePicUrl:null};e.selectBox="";e.selectChange=function(){};e.genderMale=function(){e.profileInformation.gender="male"};e.genderFemale=function(){e.profileInformation.gender="female"};e.pictureIcon=function(){$("#upload-profilePicture").trigger("click")};e.pictureHover=false;e.profilePictureHover=function(){if(e.pictureHover==false){e.pictureHover=true}else{e.pictureHover=false}};e.profilePicChanged=function(t){e.files=t.files;var n=document.getElementById("upload-profilePicture").files;for(var r=0;r<n.length;r++){if(n[r].type.match("image.*")){var i=new FileReader;i.readAsDataURL(n[r]);i.onloadend=function(e){var t=document.getElementById("profile-picture-upload");t.src=e.target.result;$("#profile-picture-upload").css("opacity","1.0")}}else{alert("File Type you are uploading is not allowed. If you want to upload video please click on Video Icon")}}};e.getUserInfo=function(){var n=$("#userId").val();var r=baseUrl+"settings/getUser?id="+n;t.get(r).success(function(t){e.profileInformation.id=t.data.id;e.profileInformation.reporterHandle=t.data.userName;e.profileInformation.firstName=t.data.firstName;e.profileInformation.lastName=t.data.lastName;e.profileInformation.about=t.data.about;e.profileInformation.country=t.data.country;e.profileInformation.city=t.data.city;e.profileInformation.gender=t.data.gender;e.profileInformation.contactNo=t.data.contactNo;e.profileInformation.profilePicUrl=t.data.profilePicUrl})};e.replacer=function(e,t){if(undefined==t)return"";return t};e.submitInfo=function(){var n=new FormData;if(null!=e.files){angular.forEach(e.files,function(e){n.append("file",e)})}if(null!=e.profileInformation){n.append("profile",JSON.stringify(e.profileInformation,e.replacer))}var r=baseUrl+"settings/saveProfile";var i=n;t.post(r,i,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(t){if(t.data.msg=="Information Saved"){$("#save-modal").modal("show");e.saveModal()}})};e.saveModal=function(){n(function(){window.location.href=baseUrl+"dashboard"},1e3)};e.initCitySearch=function(){var t={types:["(cities)"],componentRestrictions:{country:"in"}};var n=document.getElementById("city");var r=new google.maps.places.Autocomplete(n,t);var i={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"long_name",postal_code:"short_name"};google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var s=t.geometry.location.lng();var o=t.address_components[0].types[0];if("locality"==o){e.profileInformation.city=t.address_components[0][i[o]]}else{}})};e.initCountrySearch=function(){var t={types:["(regions)"]};var n=document.getElementById("country");var r=new google.maps.places.Autocomplete(n,t);var i={street_number:"short_name",route:"long_name",locality:"long_name",administrative_area_level_1:"short_name",country:"long_name",postal_code:"short_name"};google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var s=t.geometry.location.lng();for(var o=0;o<t.address_components.length;o++){var u=t.address_components[o].types[0];if("country"==u){e.profileInformation.country=t.address_components[o][i[u]]}else{}}})};e.initCitySearch();e.initCountrySearch();e.getUserInfo();$(window).keydown(function(e){if(e.keyCode==13){e.preventDefault();return false}})}]
var ProfileSettingCtrl = ["$scope", "$http", "$timeout", function(e, t, n) {
    t.defaults.headers.common["XSRF_TOKEN"] = $("#csrf").val();
    e.profileInformation = {
        id: null,
        reporterHandle: null,
        firstName: null,
        lastName: null,
        about: "",
        country: null,
        city: null,
        gender: null,
        contactNo: null,
        profilePicUrl: null
    };
    e.countryCode = '';
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
            e.profileInformation.reporterHandle = t.data.userName;
            e.profileInformation.firstName = t.data.firstName;
            e.profileInformation.lastName = t.data.lastName;
            e.profileInformation.about = t.data.about;
            e.profileInformation.country = t.data.country;
            e.profileInformation.city = t.data.city;
            e.profileInformation.gender = t.data.gender;
            e.profileInformation.contactNo = t.data.contactNo;
            e.profileInformation.profilePicUrl = t.data.profilePicUrl
        })
    };
    e.replacer = function(e, t) {
        if (undefined == t) return "";
        return t
    };
    e.submitInfo = function() {
        var n = new FormData;
        if (null != e.files) {
            angular.forEach(e.files, function(e) {
                n.append("file", e)
            })
        }
        if (null != e.profileInformation) {
            n.append("profile", JSON.stringify(e.profileInformation, e.replacer))
        }

        showLoading("Saving","Saving");
        var r = baseUrl + "settings/saveProfile";
        var i = n;
        t.post(r, i, {
            transformRequest: angular.identity,
            headers: {
                "Content-type": undefined
            }
        }).success(function(t) {
            if (t.data.msg == "Information Saved") {
                $("#save-modal").modal("show");
                e.saveModal()
            }
        })
    };
    e.saveModal = function() {
        n(function() {
            window.location.href = baseUrl + "dashboard"
        }, 1e3)
    };
    e.initCitySearch = function() {
        var t = {
            types: ["(cities)"],
            componentRestrictions: {
                country: '' + e.countryCode +''
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
            for (var o = 0; o < t.address_components.length; o++) {
            	e.countryCode = t.address_components[o].short_name;
            	console.log(e.countryCode);
                var u = t.address_components[o].types[0];
                if ("country" == u) {
                    e.profileInformation.country = t.address_components[o][i[u]]
                } else {}
            }

            e.initCitySearch();
        })
    };
    e.initCountrySearch();
    if(e.countryCode){
    	e.initCitySearch();
    }
    e.getUserInfo();
    $(window).keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false
        }
    })
}]
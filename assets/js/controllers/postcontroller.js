// var PostCtrl=["$scope","$http","$timeout","$compile",function(e,t,n,r){function i(){setTimeout(function(){e.autoSave()},2e4)}t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.files=[];e.previewData=[];e.uploadedFiles=[];e.isUploaded=false;e.PostTypeValue="";e.canSend=true;e.postAsUser=true;e.type="user";e.individual="individual";e.showSourceArea=false;e.hashtags=[];e.hashtag;e.mediaLength=false;e.post={id:$("#postId").val(),title:"",description:"",category:"",impact:"",hashtags:"",postType:"",type:"user",source:"",linkClick:"draft",location:""};e.j=0;e.locationErr=false;e.postErrorMsg="";e.imageCount=[];e.relatedPosts="";e.message={message:""};e.empty=false;e.recentPosts=[];e.checkUserMessage=function(){var e=baseUrl+"settings/checkHasSeen";t.get(e).success(function(e){console.log(e);if(e.success==true){if(e.data.code==2){$("#messageModal").modal("show")}}})};e.messageSeen=function(){var e=baseUrl+"settings/messageSeen";t.get(e).success(function(e){})};e.checkUserMessage();e.selfSource=function(){if(e.showSourceArea==false){e.showSourceArea=true;if(e.post.source==""){e.post.source=""}}else{e.showSourceArea=false;e.post.source=""}};e.validateHashtags=function(){for(var t=0;t<e.hashtags.length;t++){if(e.hashtag==e.hashtags[t]){return false}}return true};e.pushHashtags=function(t){if($("#hash").val()==""){$("#hash").addClass("error-class")}else if(t.keyCode==32||t.keyCode==13){if(e.hashtags.length<10){if(""!=e.hashtags){if(true==e.validateHashtags()){if(e.hashtag.charAt(0)=="#"){e.hashtag=e.hashtag.substring(1);e.hashtags.push(e.hashtag)}else{e.hashtags.push(e.hashtag)}}}else{if(e.hashtag.charAt(0)=="#"){e.hashtag=e.hashtag.substring(1);e.hashtags.push(e.hashtag)}else{e.hashtags.push(e.hashtag)}}}e.hashtag=""}};e.indiv=function(){e.individual=true;e.post.type="user";e.postAsUser=true};e.anonymous=function(){e.individual=false;e.post.type="anonymous";e.postAsUser=false};e.ImageIcon=function(){$("#uploadImageFile").trigger("click")};e.VideoIcon=function(){$("#uploadVideoFile").trigger("click");e.post.postType="video"};e.DismissUpload=function(){e.files=[];e.uploadedFiles=[];e.isUploaded=false;e.isImageUploaded=false;e.isVideoUploaded=false};$(document).on("click",".deleteImg",function(t){$(this).parent(".uploadImgParent").css("display","none");e.files[t.target.id]=null});e.removeVideo=function(t){$(".previousVideo").css("display","none");var n=[];n.push(e.postId);n.push(e.uploadedFiles[0]);e.removeFile(n);setTimeout(function(){e.uploadedFiles[t.$index]=null},100)};e.removeImage=function(t){$("#"+t.$index).parent(".previousImgParent").css("display","none");var n=[];n.push(e.postId);n.push(e.uploadedFiles[t.$index]);e.removeFile(n);setTimeout(function(){e.uploadedFiles[t.$index]=null},100)};e.removeFile=function(e){var n=baseUrl+"post/removeFile";t.post(n,e).success(function(e){})};e.ImagefilesChanged=function(t){e.post.postType="";e.post.postType="Image";var n=document.getElementById("uploadImageFile").files;var r=document.getElementById("image-gallery");for(var i=0;i<n.length;i++){e.imageCount[i]=i;if(null!=n){if(n[i].type.match("image.*")){var s=new FileReader;s.readAsDataURL(n[i]);s.onloadend=function(t){var n=document.createElement("div");n.className="uploadImgParent";var i=document.createElement("img");i.className="uploadImg";var s=document.createElement("img");s.className="deleteImg";s.src=baseUrl+"assets/images/delete.png";i.src=t.target.result;s.id=e.j++;n.appendChild(i);n.appendChild(s);r.appendChild(n);e.isUploaded=true;e.isImageUploaded=true;e.$apply()};e.files.push(n[i])}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";$("#postErrorModal").modal("show")}}else{if(e.files[i].type.match("image.*")){var s=new FileReader;s.readAsDataURL(e.files[i]);s.onloadend=function(t){var n=document.createElement("div");n.className="uploadImgParent";var i=document.createElement("img");i.className="uploadImg";var s=document.createElement("img");s.className="deleteImg";s.src=baseUrl+"assets/images/delete.png";i.src=t.target.result;s.id=e.j++;r.appendChild(i);$("#image-gallery > img").css("height","150");$("#image-gallery > img").css("width","200");$("#image-gallery > img").css("padding","5");$("#image-gallery > img").addClass("col-lg-3");e.isUploaded=true;e.isImageUploaded=true;e.$apply()}}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";$("#postErrorModal").modal("show")}}}};e.VideofilesChanged=function(t){e.files=t.files;e.post.postType="Video";var n=document.getElementById("uploadVideoFile").files;var r=document.getElementById("video-gallery");for(var i=0;i<n.length;i++){if(n[i].type.match("video.*")){var s=new FileReader;s.readAsDataURL(n[i]);s.onloadend=function(t){var n=document.createElement("video");n.src=t.target.result;n.controls=true;r.appendChild(n);$("#video-gallery > video").css("height","250");$("#video-gallery > video").css("width","100%");$("#video-gallery > video").css("padding","5");e.isUploaded=true;e.isVideoUploaded=true;e.$apply()}}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Image Icon";$("#postErrorModal").modal("show")}}};e.changed=function(){e.isChanged=true};e.getPost=function(){var n=baseUrl+"post/getPost";var r=e.post.id;t.post(n,r).success(function(t){if(t.success==true){e.post=t.data;e.individual=t.data.individual;e.hashtags=e.post.hashtags;if(t.data.files.length==0){}else{if(e.post.postType=="Video"){e.uploadedFiles=t.data.files;if(null!=e.uploadedFiles){e.isVideoUploaded=true;e.isUploaded=true;e.videoThere=true}}else if(e.post.postType=="Image"){e.uploadedFiles=t.data.files;if(null!=e.uploadedFiles){if(e.uploadedFiles.length>2){e.mediaLength=true}e.isImageUploaded=true;e.isUploaded=true}}}}})};e.autoSave=function(n){if(true!=e.canSend){$("#previewgenerated").modal("show")}e.draftSaved=true;if(e.post.title==""){$("#title").attr("title","The field cannot be blank");$("#title").addClass("error-class");return false}if(e.post.category==""){$("#title").attr("title","The field cannot be blank");$("#category").addClass("error-class");return false}if(e.post.impact==""){$("#title").attr("title","The field cannot be blank");$("#impact").addClass("error-class");return false}if(!e.isChanged){$(".upload").attr("title","The field cannot be blank");$("#title").addClass("error-class");$("#category").addClass("error-class");$("#impact").addClass("error-class");$("#hashtags").addClass("error-class");return false}if("video"==e.post.postType){e.files=document.getElementById("uploadVideoFile").files}if(e.isUploaded==true){e.post.hashtags=JSON.stringify(e.hashtags);e.post.location=e.location.lat+","+e.location.lng;if(e.post.location=="0,0"||e.post.location==""){$(".locationErr").focus();e.locationErr=true;return false}if(n=="previewPost"){e.post.linkClick="";e.post.linkClick="previewPost";$("#previewModal").modal("show")}else if(n=="drafts"){e.post.linkClick="";e.post.linkClick="draft"}var r=new FormData;angular.forEach(e.files,function(e){if(e!=null){r.append("files[]",e)}});if(null!=e.uploadedFiles){angular.forEach(e.uploadedFiles,function(e){if(e!=null){r.append("files[]",e)}})}e.isUploaded=true;r.append("post",JSON.stringify(e.post));var i=baseUrl+"drafts/autoSaveDraft";var s=r;if(true==e.canSend){e.canSend=false;t.post(i,s,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(t){if(t.success==true){if(t.data.target=="draft"){e.canSend=true;e.post.id=t.data.data.id;e.isChanged=true;e.draftSaved=true;$("#savedModal").modal("show");e.saveModal()}else if(t.data.target=="preview"){e.isChanged=true;e.draftSaved=true;e.canSend=true;e.post.id=t.data.data.id;e.previewData=t.data.data;if(e.previewData.postType=="Image"){e.previewData.showImage=true}else{e.previewData.showImage=false}if(t.data.data.files.length>1){e.mediaLength=true}$("#uploadImageFile").val("");$("#uploadVideoFile").val("");e.files=[];setTimeout(function(){var e=$(".preview-bxslider").bxSlider({auto:true})},300);$("#previewModal").modal("hide");$("#previewgenerated").modal("show")}}})}}else{$(".saveDraft").attr("title","A file is required to save draft. Please upload a file and try again!");$(".saveDraft").tooltip("show")}};e.saveModal=function(){n(function(){window.location.href=baseUrl+"dashboard"},3e3)};e.submitPost=function(n){if(e.post.title==""){$("#title").attr("title","The field cannot be blank");$("#title").addClass("error-class");return false}if(e.post.category==""){$("#title").attr("title","The field cannot be blank");$("#category").addClass("error-class");return false}if(e.post.impact==""){$("#title").attr("title","The field cannot be blank");$("#impact").addClass("error-class");return false}if(!e.isChanged){$(".upload").attr("title","The field cannot be blank");$("#title").addClass("error-class");$("#category").addClass("error-class");$("#impact").addClass("error-class");$("#hashtags").addClass("error-class");return false}if(e.isUploaded==true){if("video"==e.post.postType){e.files=document.getElementById("uploadVideoFile").files}e.post.hashtags=JSON.stringify(e.hashtags);var r=new FormData;angular.forEach(e.files,function(e){if(e!=null){r.append("files[]",e)}});if(null!=e.uploadedFiles){angular.forEach(e.uploadedFiles,function(e){if(e!=null){r.append("files[]",e)}})}e.isUploaded=true;e.post.location=e.location.lat+","+e.location.lng;if(e.post.location=="0,0"||e.post.location==""){$(".locationErr").focus();e.locationErr=true;return false}console.log(e.post);$("#waitingModal").modal("show");r.append("post",JSON.stringify(e.post));var i=baseUrl+"post/postSubmitted";var s=r;if(true==e.canSend){e.canSend=false;t.post(i,s,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(e){if(e.success==true){window.location=baseUrl+"post?upload=success"}})}}else{}};e.getRecentPosts=function(){var n=baseUrl+"post/getRecentPosts?howMany=3";t.get(n).success(function(t){if(null!=t.data){e.recentPosts=t.data;for(var n=0;n<e.recentPosts.length;n++){if(e.recentPosts[n].postType=="Image"){e.showImage=true;e.recentPosts[n]["showImage"]=e.showImage;e.showVideo=false}else{e.showVideo=true;e.showImage=false;e.recentPosts[n]["showVideo"]=e.showVideo}}}else{e.empty=true}})};e.getRelatedPosts=function(n){var r=baseUrl+"post/getRelatedPosts?howMany=3&categId="+n;t.get(r).success(function(t){if(null!=t.data){e.relatedPosts=t.data}})};e.postId=$("#postId").val();e.location={lat:0,lng:0,address:""};e.removeHashtag=function(t){e.hashtags.splice(t,1)};e.getLocation=function(){if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e.updatePosition,e.handleDenyLocation)}};e.handleDenyLocation=function(){e.locationErr=true};e.geocoder=new google.maps.Geocoder;e.updatePosition=function(t){e.post.location=t.coords.latitude+","+t.coords.longitude;e.location.lat=t.coords.latitude;e.location.lng=t.coords.longitude;var n=new google.maps.LatLng(e.location.lat,e.location.lng);e.geocoder.geocode({latLng:n},function(t,n){if(t&&t.length>0){e.locationErr=false;if(t.length>1){e.location.address=t[1].formatted_address}else{e.location.address=t[0].formatted_address}e.post.location=e.location}else{var r=e.location.lat+", "+e.location.lng;e.location.address="Unable to find address at given location. ("+r+")"}e.$apply();e.initMap();e.initCitySearch()});e.$apply()};e.changeLocation=function(){$("#locationModal").modal("show");setTimeout(function(){google.maps.event.trigger(e.map,"resize")},100)};e.map="";e.marker="";e.initMap=function(){var t={center:new google.maps.LatLng(e.location.lat,e.location.lng),zoom:16};e.map=new google.maps.Map(document.getElementById("map-canvas"),t);e.marker=new google.maps.Marker({map:e.map,draggable:true,animation:google.maps.Animation.DROP,position:new google.maps.LatLng(e.location.lat,e.location.lng)});google.maps.event.addListener(e.marker,"click",e.toggleBounce);google.maps.event.addListener(e.marker,"dragend",function(t){e.location.lat=t.latLng.lat();e.location.lng=t.latLng.lng();var n=t.latLng;var r=e.location.lat+", "+e.location.lng;e.post.position=r;e.geocoder.geocode({latLng:n},function(t,n){if(t&&t.length>0){e.location.address=t[0].formatted_address}else{e.location.address="Unable to find address at given location. ("+r+")"}e.$apply()})})};e.toggleBounce=function(){if(e.marker.getAnimation()!=null){e.marker.setAnimation(null)}else{e.marker.setAnimation(google.maps.Animation.BOUNCE)}};e.initCitySearch=function(){var t={};var n=document.getElementById("location-select-text");var r=new google.maps.places.Autocomplete(n,t);google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var i=t.geometry.location.lng();e.location.lat=n;e.location.lng=i;e.location.address=t.formatted_address;e.$apply()})};e.getRecentPosts();e.getLocation();e.initCitySearch();e.getRelatedPosts();if(null!=e.post.id||""!=e.post.id){e.getPost()}if(e.post.id==""){e.isChanged=false}else{e.isChanged=true}$(window).keydown(function(e){if(e.keyCode==13){e.preventDefault();return false}})}]

var PostCtrl = ["$scope", "$http", "$timeout", "$compile", function(e, t, n, r) {
    function i() {
        setTimeout(function() {
            e.autoSave()
        }, 2e4)
    }
    t.defaults.headers.common["XSRF_TOKEN"] = $("#csrf").val();
    e.files = [];
    e.previewData = [];
    e.uploadedFiles = [];
    e.count = '';
    e.isUploaded = false;
    e.PostTypeValue = "";
    e.canSend = true;
    e.postAsUser = true;
    e.type = "user";
    e.individual = "individual";
    e.showSourceArea = false;
    e.hashtags = [];
    e.hashtag;
    e.mediaLength = false;
    e.post = {
        id: $("#postId").val(),
        title: "",
        description: "",
        category: "",
        impact: "",
        hashtags: "",
        postType: "",
        type: "user",
        source: "",
        linkClick: "draft",
        location: ""
    };
    e.j = 0;
    e.locationErr = false;
    e.postErrorMsg = "";
    e.imageCount = [];
    e.relatedPosts = "";
    e.message = {
        message: ""
    };
    e.empty = false;
    e.recentPosts = [];
    e.checkUserMessage = function() {
        var e = baseUrl + "settings/checkHasSeen";
        t.get(e).success(function(e) {
            console.log(e);
            if (e.success == true) {
                if (e.data.code == 2) {
                    $("#messageModal").modal("show")
                }
            }
        })
    };
    e.messageSeen = function() {
        var e = baseUrl + "settings/messageSeen";
        t.get(e).success(function(e) {})
    };
    e.checkUserMessage();
    e.selfSource = function() {
        if (e.showSourceArea == false) {
            e.showSourceArea = true;
            if (e.post.source == "Self") {
                e.post.source = ""
            }
        } else {
            e.showSourceArea = false;
            e.post.source = "Self"
        }
    };
    e.validateHashtags = function() {
        for (var t = 0; t < e.hashtags.length; t++) {
            if (e.hashtag == e.hashtags[t]) {
                return false
            }
        }
        return true
    };
    e.pushHashtags = function(t) {
        if ($("#hash").val() == "") {
            $("#hash").addClass("error-class")
        } else if (t.keyCode == 32 || t.keyCode == 13) {
            if (e.hashtags.length < 10) {
                if ("" != e.hashtags) {
                    if (true == e.validateHashtags()) {
                        if (e.hashtag.charAt(0) == "#") {
                            e.hashtag = e.hashtag.substring(1);
                            e.hashtags.push(e.hashtag)
                        } else {
                            e.hashtags.push(e.hashtag)
                        }
                    }
                } else {
                    if (e.hashtag.charAt(0) == "#") {
                        e.hashtag = e.hashtag.substring(1);
                        e.hashtags.push(e.hashtag)
                    } else {
                        e.hashtags.push(e.hashtag)
                    }
                }
            }else{
            	$("#hash").attr("title", "You have already entered 10 hashtags. You may enter only 10");
            	$("#hash").tooltip('show');
            }
            e.hashtag = ""
        }
    };
    e.indiv = function() {
        e.individual = true;
        e.post.type = "user";
        e.postAsUser = true
    };
    e.anonymous = function() {
        e.individual = false;
        e.post.type = "anonymous";
        e.postAsUser = false
    };
    e.ImageIcon = function() {
        $("#uploadImageFile").trigger("click")
    };
    e.VideoIcon = function() {
        $("#uploadVideoFile").trigger("click");
        e.post.postType = "video"
    };
    e.DismissUpload = function() {
        e.files = [];
        e.uploadedFiles = [];
        e.isUploaded = false;
        e.isImageUploaded = false;
        e.isVideoUploaded = false
    };
    $(document).on("click", ".deleteImg", function(t) {
        $(this).parent(".uploadImgParent").css("display", "none");
        // console.log($('#uploadImageFile').val());
        // console.log(document.getElementById("uploadImageFile").files);
        // e.files[t.target.id] = null;
        var key = t.target.id;
        e.files.splice(t.target.id - e.j,1);
        delete document.getElementById("uploadImageFile").files.key;
        console.log(e.files);
        if(e.files[0] == null){
        	e.files.length = 0;
        	$('#uploadImageFile').val(null);
        	document.getElementById("uploadImageFile").files = {};
        	console.log($('#uploadImageFile').val());
        	e.isUploaded = false;
        	e.isImageUploaded = false;
        }
        console.log(document.getElementById("uploadImageFile").files);
        console.log(e.files);

        e.$apply();
    });
    e.removeVideo = function(t) {
        $(".previousVideo").css("display", "none");
        var n = [];
        n.push(e.postId);
        n.push(e.uploadedFiles[0]);
        e.removeFile(n);
        setTimeout(function() {
            e.uploadedFiles[t.$index] = null
        }, 100)
    };
    e.removeImage = function(t) {
        $("#" + t.$index).parent(".previousImgParent").css("display", "none");
        var n = [];
        n.push(e.postId);
        n.push(e.uploadedFiles[t.$index]);
        e.removeFile(n);
        setTimeout(function() {
            e.uploadedFiles[t.$index] = null
        }, 100)
    };
    e.removeFile = function(e) {
        var n = baseUrl + "post/removeFile";
        t.post(n, e).success(function(e) {})
    };
    e.ImagefilesChanged = function(t) {
        e.post.postType = "";
        e.post.postType = "Image";
        var n = document.getElementById("uploadImageFile").files;
        var r = document.getElementById("image-gallery");
        for (var i = 0; i < n.length; i++) {
            e.imageCount[i] = i;
            if (null != n) {
                if (n[i].type.match("image.*")) {
                    var s = new FileReader;
                    s.readAsDataURL(n[i]);
                    s.onloadend = function(t) {
                        var n = document.createElement("div");
                        n.className = "uploadImgParent";
                        var i = document.createElement("img");
                        i.className = "uploadImg";
                        var s = document.createElement("img");
                        s.className = "deleteImg";
                        s.src = baseUrl + "assets/images/delete.png";
                        i.src = t.target.result;
                        s.id = e.j++;
                        n.appendChild(i);
                        n.appendChild(s);
                        r.appendChild(n);
                        e.isUploaded = true;
                        e.isImageUploaded = true;
                        e.$apply()
                    };
                    e.files.push(n[i])
                } else {
                    e.postErrorMsg = "File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";
                    $("#postErrorModal").modal("show")
                }
            } else {
                if (e.files[i].type.match("image.*")) {
                    var s = new FileReader;
                    s.readAsDataURL(e.files[i]);
                    s.onloadend = function(t) {
                        var n = document.createElement("div");
                        n.className = "uploadImgParent";
                        var i = document.createElement("img");
                        i.className = "uploadImg";
                        var s = document.createElement("img");
                        s.className = "deleteImg";
                        s.src = baseUrl + "assets/images/delete.png";
                        i.src = t.target.result;
                        s.id = e.j++;
                        r.appendChild(i);
                        $("#image-gallery > img").css("height", "150");
                        $("#image-gallery > img").css("width", "200");
                        $("#image-gallery > img").css("padding", "5");
                        $("#image-gallery > img").addClass("col-lg-3");
                        e.isUploaded = true;
                        e.isImageUploaded = true;
                        e.$apply()
                    }
                } else {
                    e.postErrorMsg = "File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";
                    $("#postErrorModal").modal("show")
                }
            }
        }
    };
    e.VideofilesChanged = function(t) {
        e.files = t.files;
        e.post.postType = "Video";
        var n = document.getElementById("uploadVideoFile").files;
        var r = document.getElementById("video-gallery");
        for (var i = 0; i < n.length; i++) {
            if (n[i].type.match("video.*")) {
                var s = new FileReader;
                s.readAsDataURL(n[i]);
                s.onloadend = function(t) {
                    var n = document.createElement("video");
                    n.src = t.target.result;
                    n.controls = true;
                    r.appendChild(n);
                    $("#video-gallery > video").css("height", "250");
                    $("#video-gallery > video").css("width", "100%");
                    $("#video-gallery > video").css("padding", "5");
                    e.isUploaded = true;
                    e.isVideoUploaded = true;
                    e.$apply()
                }
            } else {
                e.postErrorMsg = "File Type you are uploading is not allowed. If you want to upload video please click on Image Icon";
                $("#postErrorModal").modal("show")
            }
        }
    };
    e.changed = function() {
        e.isChanged = true
    };
    e.getPost = function() {
        var n = baseUrl + "post/getPost";
        var r = e.post.id;
        t.post(n, r).success(function(t) {
            if (t.success == true) {
                e.post = t.data;
                e.individual = t.data.individual;
                e.hashtags = e.post.hashtags;
                if (t.data.files.length == 0) {} else {
                    if (e.post.postType == "Video") {
                        e.uploadedFiles = t.data.files;
                        if (null != e.uploadedFiles) {
                            e.isVideoUploaded = true;
                            e.isUploaded = true;
                            e.videoThere = true
                        }
                    } else if (e.post.postType == "Image") {
                        e.uploadedFiles = t.data.files;
                        if (null != e.uploadedFiles) {
                            if (e.uploadedFiles.length > 2) {
                                e.mediaLength = true
                            }
                            e.isImageUploaded = true;
                            e.isUploaded = true;
                        }
                    }
                }

                e.location.address = t.data.address;
                e.location.lat = t.data.latitude;
                e.location.lng = t.data.longitude;

                if(e.post.source == "Self"){
                	e.self = true;
                }else{
                	e.self = false;
                	$('#post-source-checkbox').attr('checked',false);
                	e.showSourceArea = true;
                }
                console.log(e.post);
            }
        })
    };
    e.autoSave = function(n) {
    	console.log('kjdsn');
        if (true != e.canSend) {
            $("#previewgenerated").modal("show")
        }
        e.draftSaved = true;
        if (e.post.title == "") {
            $("#title").attr("title", "The field cannot be blank");
            $("#title").addClass("error-class");
            $("#title").tooltip('show');
            return false
        }
        if (e.post.category == "") {
            $("#category").attr("title", "The field cannot be blank");
            $("#category").tooltip('show');
            $("#category").addClass("error-class");
            return false
        }
        if (e.post.impact == "") {
            $("#impact").attr("title", "The field cannot be blank");
            $("#impact").tooltip('show');
            $("#impact").addClass("error-class");
            return false
        }
        if (!e.isChanged) {
            $(".upload").attr("title", "The field cannot be blank");
            $("#title").addClass("error-class");
            $("#category").addClass("error-class");
            $("#impact").addClass("error-class");
            $("#hashtags").addClass("error-class");
            return false
        }
        if ("video" == e.post.postType) {
            e.files = document.getElementById("uploadVideoFile").files
        }

        if (e.isUploaded == true) {
            e.post.hashtags = JSON.stringify(e.hashtags);
            e.post.location = e.location.lat + "," + e.location.lng;
            console.log(e.post.location);
            if (e.post.location == "0,0" || e.post.location == "") {
                $(".locationErr").focus();
                e.locationErr = true;
                return false
            }

            // console.log(n);

            if (n == "previewPost") {
                e.post.linkClick = "";
                e.post.linkClick = "previewPost";
                $("#previewModal").modal("show")
            } else if (n == "drafts") {
                e.post.linkClick = "";
                e.post.linkClick = "draft"
            }
            var r = new FormData;
            angular.forEach(e.files, function(e) {
                if (e != null) {
                    r.append("files[]", e)
                }
            });
            if (null != e.uploadedFiles) {
                angular.forEach(e.uploadedFiles, function(e) {
                    if (e != null) {
                        r.append("files[]", e)
                    }
                })
            }
            e.isUploaded = true;
            r.append("post", JSON.stringify(e.post));
            var i = baseUrl + "drafts/autoSaveDraft";
            var s = r;
            if (true == e.canSend) {
                e.canSend = false;
                t.post(i, s, {
                    transformRequest: angular.identity,
                    headers: {
                        "Content-type": undefined
                    }
                }).success(function(t) {
                    console.log(t);
                    if (t.success == true) {
                        if (t.data.target == "draft") {
                            e.canSend = true;
                            e.post.id = t.data.data.id;
                            e.isChanged = true;
                            e.draftSaved = true;
                            $("#savedModal").modal("show");
                            e.saveModal()
                        } else if (t.data.target == "preview") {
                            e.isChanged = true;
                            e.draftSaved = true;
                            e.canSend = true;
                            e.post.id = t.data.data.id;
                            console.log(e.post);
                            e.previewData = t.data.data;
                            if (e.previewData.postType == "Image") {
                                e.previewData.showImage = true
                            } else {
                                e.previewData.showImage = false
                            }
                            if (t.data.data.files.length > 1) {
                                e.mediaLength = true
                            }
                            $("#uploadImageFile").val("");
                            $("#uploadVideoFile").val("");
                            e.files = [];
                            setTimeout(function() {
                                var e = $(".preview-bxslider").bxSlider({
                                    auto: true
                                })
                            }, 300);
                            $("#previewModal").modal("hide");
                            $("#previewgenerated").modal("show")
                        }
                    }
                })
            }
        } else {
            $(".saveDraft").attr("title", "A file is required to save draft. Please upload a file and try again!");
            $(".saveDraft").tooltip("show")
        }
    };
    e.saveModal = function() {
        n(function() {
            window.location.href = baseUrl + "dashboard"
        }, 3e3)
    };
    e.submitPost = function(n) {
        if (e.post.title == "") {
            $("#title").attr("title", "The field cannot be blank");
            $("#title").addClass("error-class");
            return false
        }
        if (e.post.category == "") {
            $("#title").attr("title", "The field cannot be blank");
            $("#category").addClass("error-class");
            return false
        }
        if (e.post.impact == "") {
            $("#title").attr("title", "The field cannot be blank");
            $("#impact").addClass("error-class");
            return false
        }
        if (!e.isChanged) {
            $(".upload").attr("title", "The field cannot be blank");
            $("#title").addClass("error-class");
            $("#category").addClass("error-class");
            $("#impact").addClass("error-class");
            $("#hashtags").addClass("error-class");
            return false
        }
        if (e.isUploaded == true) {
            if ("video" == e.post.postType) {
                e.files = document.getElementById("uploadVideoFile").files
            }
            e.post.hashtags = JSON.stringify(e.hashtags);
            var r = new FormData;
            angular.forEach(e.files, function(e) {
                if (e != null) {
                    r.append("files[]", e)
                }
            });
            if (null != e.uploadedFiles) {
                angular.forEach(e.uploadedFiles, function(e) {
                    if (e != null) {
                        r.append("files[]", e)
                    }
                })
            }
            e.isUploaded = true;
            e.post.location = e.location.lat + "," + e.location.lng;
            if (e.post.location == "0,0" || e.post.location == "") {
                $(".locationErr").focus();
                e.locationErr = true;
                return false
            }
            console.log(e.post);
            $("#waitingModal").modal("show");
            r.append("post", JSON.stringify(e.post));
            var i = baseUrl + "post/postSubmitted";
            var s = r;
            if (true == e.canSend) {
                e.canSend = false;
                t.post(i, s, {
                    transformRequest: angular.identity,
                    headers: {
                        "Content-type": undefined
                    }
                }).success(function(e) {
                    if (e.success == true) {
                        if(e.data.postType == "Image"){
                            window.location = baseUrl + "post?upload=success"
                        }else if(e.data.postType == "Video"){
                            window.location = baseUrl + "post?upload=v"
                        }
                    }
                })
            }
        } else {}
    };
    e.getRecentPosts = function() {
        var n = baseUrl + "post/getRecentPosts?howMany=3";
        t.get(n).success(function(t) {
            if (null != t.data) {
                e.recentPosts = t.data;
                for (var n = 0; n < e.recentPosts.length; n++) {
                    if (e.recentPosts[n].postType == "Image") {
                        e.showImage = true;
                        e.recentPosts[n]["showImage"] = e.showImage;
                        e.showVideo = false
                    } else {
                        e.showVideo = true;
                        e.showImage = false;
                        e.recentPosts[n]["showVideo"] = e.showVideo
                    }
                }
            } else {
                e.empty = true
            }
        })
    };
    e.getRelatedPosts = function(n) {
        var r = baseUrl + "post/getRelatedPosts?howMany=3&categId=" + n;
        t.get(r).success(function(t) {
            if (null != t.data) {
                e.relatedPosts = t.data
            }
        })
    };
    e.postId = $("#postId").val();
    e.location = {
        lat: 0,
        lng: 0,
        address: ""
    };
    e.removeHashtag = function(t) {
        e.hashtags.splice(t, 1)
    };
    e.getLocation = function() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(e.updatePosition, e.handleDenyLocation)
        }
    };
    e.handleDenyLocation = function() {
        e.locationErr = true
    };
    e.geocoder = new google.maps.Geocoder;
    e.updatePosition = function(t) {
        e.post.location = t.coords.latitude + "," + t.coords.longitude;
        e.location.lat = t.coords.latitude;
        e.location.lng = t.coords.longitude;
        var n = new google.maps.LatLng(e.location.lat, e.location.lng);
        e.geocoder.geocode({
            latLng: n
        }, function(t, n) {
            if (t && t.length > 0) {
                e.locationErr = false;
                if (t.length > 1) {
                    e.location.address = t[1].formatted_address
                } else {
                    e.location.address = t[0].formatted_address
                }
                e.post.location = e.location
            } else {
                var r = e.location.lat + ", " + e.location.lng;
                e.location.address = "Unable to find address at given location. (" + r + ")"
            }
            e.$apply();
            e.initMap();
            e.initCitySearch()
        });
        e.$apply()
    };
    e.changeLocation = function() {
        $("#locationModal").modal("show");
        setTimeout(function() {
            google.maps.event.trigger(e.map, "resize")
        }, 100)
    };
    e.map = "";
    e.marker = "";
    e.initMap = function() {
        var t = {
            center: new google.maps.LatLng(e.location.lat, e.location.lng),
            zoom: 16
        };
        e.map = new google.maps.Map(document.getElementById("map-canvas"), t);
        e.marker = new google.maps.Marker({
            map: e.map,
            draggable: true,
            animation: google.maps.Animation.DROP,
            position: new google.maps.LatLng(e.location.lat, e.location.lng)
        });
        google.maps.event.addListener(e.marker, "click", e.toggleBounce);
        google.maps.event.addListener(e.marker, "dragend", function(t) {
            e.location.lat = t.latLng.lat();
            e.location.lng = t.latLng.lng();
            var n = t.latLng;
            var r = e.location.lat + ", " + e.location.lng;
            e.post.position = r;
            e.geocoder.geocode({
                latLng: n
            }, function(t, n) {
                if (t && t.length > 0) {
                    e.location.address = t[0].formatted_address
                } else {
                    e.location.address = "Unable to find address at given location. (" + r + ")"
                }
                e.$apply()
            })
        })
    };
    e.toggleBounce = function() {
        if (e.marker.getAnimation() != null) {
            e.marker.setAnimation(null)
        } else {
            e.marker.setAnimation(google.maps.Animation.BOUNCE)
        }
    };
    e.initCitySearch = function() {
        var t = {};
        var n = document.getElementById("location-select-text");
        var r = new google.maps.places.Autocomplete(n, t);
        google.maps.event.addListener(r, "place_changed", function() {
            var t = r.getPlace();
            var n = t.geometry.location.lat();
            var i = t.geometry.location.lng();
            e.location.lat = n;
            e.location.lng = i;
            e.location.address = t.formatted_address;
            e.$apply()
        })
    };
    e.getRecentPosts();
    e.getLocation();
    e.initCitySearch();
    e.getRelatedPosts();
    if (null != e.post.id || "" != e.post.id) {
        e.getPost()
    }
    if (e.post.id == "") {
        e.isChanged = false
    } else {
        e.isChanged = true
    }
    $(window).keydown(function(e) {
        if (e.keyCode == 13) {
            e.preventDefault();
            return false
        }
    })
}]

// var PostCtrl=["$scope","$http","$timeout","$compile",function(e,t,n,r){function i(){setTimeout(function(){e.autoSave()},2e4)}t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.files=[];e.previewData=[];e.uploadedFiles=[];e.count="";e.isUploaded=false;e.PostTypeValue="";e.canSend=true;e.postAsUser=true;e.type="user";e.individual="individual";e.showSourceArea=false;e.hashtags=[];e.hashtag;e.mediaLength=false;e.post={id:$("#postId").val(),title:"",description:"",category:"",impact:"",hashtags:"",postType:"",type:"user",source:"",linkClick:"draft",location:""};e.j=0;e.locationErr=false;e.postErrorMsg="";e.imageCount=[];e.relatedPosts="";e.message={message:""};e.empty=false;e.recentPosts=[];e.checkUserMessage=function(){var e=baseUrl+"settings/checkHasSeen";t.get(e).success(function(e){console.log(e);if(e.success==true){if(e.data.code==2){$("#messageModal").modal("show")}}})};e.messageSeen=function(){var e=baseUrl+"settings/messageSeen";t.get(e).success(function(e){})};e.checkUserMessage();e.selfSource=function(){if(e.showSourceArea==false){e.showSourceArea=true;if(e.post.source==""){e.post.source=""}}else{e.showSourceArea=false;e.post.source=""}};e.validateHashtags=function(){for(var t=0;t<e.hashtags.length;t++){if(e.hashtag==e.hashtags[t]){return false}}return true};e.pushHashtags=function(t){if($("#hash").val()==""){$("#hash").addClass("error-class")}else if(t.keyCode==32||t.keyCode==13){if(e.hashtags.length<10){if(""!=e.hashtags){if(true==e.validateHashtags()){if(e.hashtag.charAt(0)=="#"){e.hashtag=e.hashtag.substring(1);e.hashtags.push(e.hashtag)}else{e.hashtags.push(e.hashtag)}}}else{if(e.hashtag.charAt(0)=="#"){e.hashtag=e.hashtag.substring(1);e.hashtags.push(e.hashtag)}else{e.hashtags.push(e.hashtag)}}}e.hashtag=""}};e.indiv=function(){e.individual=true;e.post.type="user";e.postAsUser=true};e.anonymous=function(){e.individual=false;e.post.type="anonymous";e.postAsUser=false};e.ImageIcon=function(){$("#uploadImageFile").trigger("click")};e.VideoIcon=function(){$("#uploadVideoFile").trigger("click");e.post.postType="video"};e.DismissUpload=function(){e.files=[];e.uploadedFiles=[];e.isUploaded=false;e.isImageUploaded=false;e.isVideoUploaded=false};$(document).on("click",".deleteImg",function(t){$(this).parent(".uploadImgParent").css("display","none");var n=t.target.id;e.files.splice(t.target.id-e.j,1);delete document.getElementById("uploadImageFile").files.key;console.log(e.files);if(e.files[0]==null){e.files.length=0;$("#uploadImageFile").val(null);document.getElementById("uploadImageFile").files={};console.log($("#uploadImageFile").val());e.isUploaded=false;e.isImageUploaded=false}console.log(document.getElementById("uploadImageFile").files);console.log(e.files);e.$apply()});e.removeVideo=function(t){$(".previousVideo").css("display","none");var n=[];n.push(e.postId);n.push(e.uploadedFiles[0]);e.removeFile(n);setTimeout(function(){e.uploadedFiles[t.$index]=null},100)};e.removeImage=function(t){$("#"+t.$index).parent(".previousImgParent").css("display","none");var n=[];n.push(e.postId);n.push(e.uploadedFiles[t.$index]);e.removeFile(n);setTimeout(function(){e.uploadedFiles[t.$index]=null},100)};e.removeFile=function(e){var n=baseUrl+"post/removeFile";t.post(n,e).success(function(e){})};e.ImagefilesChanged=function(t){e.post.postType="";e.post.postType="Image";var n=document.getElementById("uploadImageFile").files;var r=document.getElementById("image-gallery");for(var i=0;i<n.length;i++){e.imageCount[i]=i;if(null!=n){if(n[i].type.match("image.*")){var s=new FileReader;s.readAsDataURL(n[i]);s.onloadend=function(t){var n=document.createElement("div");n.className="uploadImgParent";var i=document.createElement("img");i.className="uploadImg";var s=document.createElement("img");s.className="deleteImg";s.src=baseUrl+"assets/images/delete.png";i.src=t.target.result;s.id=e.j++;n.appendChild(i);n.appendChild(s);r.appendChild(n);e.isUploaded=true;e.isImageUploaded=true;e.$apply()};e.files.push(n[i])}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";$("#postErrorModal").modal("show")}}else{if(e.files[i].type.match("image.*")){var s=new FileReader;s.readAsDataURL(e.files[i]);s.onloadend=function(t){var n=document.createElement("div");n.className="uploadImgParent";var i=document.createElement("img");i.className="uploadImg";var s=document.createElement("img");s.className="deleteImg";s.src=baseUrl+"assets/images/delete.png";i.src=t.target.result;s.id=e.j++;r.appendChild(i);$("#image-gallery > img").css("height","150");$("#image-gallery > img").css("width","200");$("#image-gallery > img").css("padding","5");$("#image-gallery > img").addClass("col-lg-3");e.isUploaded=true;e.isImageUploaded=true;e.$apply()}}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Video Icon";$("#postErrorModal").modal("show")}}}};e.VideofilesChanged=function(t){e.files=t.files;e.post.postType="Video";var n=document.getElementById("uploadVideoFile").files;var r=document.getElementById("video-gallery");for(var i=0;i<n.length;i++){if(n[i].type.match("video.*")){var s=new FileReader;s.readAsDataURL(n[i]);s.onloadend=function(t){var n=document.createElement("video");n.src=t.target.result;n.controls=true;r.appendChild(n);$("#video-gallery > video").css("height","250");$("#video-gallery > video").css("width","100%");$("#video-gallery > video").css("padding","5");e.isUploaded=true;e.isVideoUploaded=true;e.$apply()}}else{e.postErrorMsg="File Type you are uploading is not allowed. If you want to upload video please click on Image Icon";$("#postErrorModal").modal("show")}}};e.changed=function(){e.isChanged=true};e.getPost=function(){var n=baseUrl+"post/getPost";var r=e.post.id;t.post(n,r).success(function(t){if(t.success==true){e.post=t.data;e.individual=t.data.individual;e.hashtags=e.post.hashtags;if(t.data.files.length==0){}else{if(e.post.postType=="Video"){e.uploadedFiles=t.data.files;if(null!=e.uploadedFiles){e.isVideoUploaded=true;e.isUploaded=true;e.videoThere=true}}else if(e.post.postType=="Image"){e.uploadedFiles=t.data.files;if(null!=e.uploadedFiles){if(e.uploadedFiles.length>2){e.mediaLength=true}e.isImageUploaded=true;e.isUploaded=true}}}if(e.post.source=="Self"){e.self=true}else{e.self=false;$("#post-source-checkbox").attr("checked",false);e.showSourceArea=true}}})};e.autoSave=function(n){if(true!=e.canSend){$("#previewgenerated").modal("show")}e.draftSaved=true;if(e.post.title==""){$("#title").attr("title","The field cannot be blank");$("#title").addClass("error-class");return false}if(e.post.category==""){$("#title").attr("title","The field cannot be blank");$("#category").addClass("error-class");return false}if(e.post.impact==""){$("#title").attr("title","The field cannot be blank");$("#impact").addClass("error-class");return false}if(!e.isChanged){$(".upload").attr("title","The field cannot be blank");$("#title").addClass("error-class");$("#category").addClass("error-class");$("#impact").addClass("error-class");$("#hashtags").addClass("error-class");return false}if("video"==e.post.postType){e.files=document.getElementById("uploadVideoFile").files}if(e.isUploaded==true){e.post.hashtags=JSON.stringify(e.hashtags);e.post.location=e.location.lat+","+e.location.lng;if(e.post.location=="0,0"||e.post.location==""){$(".locationErr").focus();e.locationErr=true;return false}if(n=="previewPost"){e.post.linkClick="";e.post.linkClick="previewPost";$("#previewModal").modal("show")}else if(n=="drafts"){e.post.linkClick="";e.post.linkClick="draft"}var r=new FormData;angular.forEach(e.files,function(e){if(e!=null){r.append("files[]",e)}});if(null!=e.uploadedFiles){angular.forEach(e.uploadedFiles,function(e){if(e!=null){r.append("files[]",e)}})}e.isUploaded=true;r.append("post",JSON.stringify(e.post));var i=baseUrl+"drafts/autoSaveDraft";var s=r;if(true==e.canSend){e.canSend=false;t.post(i,s,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(t){if(t.success==true){if(t.data.target=="draft"){e.canSend=true;e.post.id=t.data.data.id;e.isChanged=true;e.draftSaved=true;$("#savedModal").modal("show");e.saveModal()}else if(t.data.target=="preview"){e.isChanged=true;e.draftSaved=true;e.canSend=true;e.post.id=t.data.data.id;e.previewData=t.data.data;if(e.previewData.postType=="Image"){e.previewData.showImage=true}else{e.previewData.showImage=false}if(t.data.data.files.length>1){e.mediaLength=true}$("#uploadImageFile").val("");$("#uploadVideoFile").val("");e.files=[];setTimeout(function(){var e=$(".preview-bxslider").bxSlider({auto:true})},300);$("#previewModal").modal("hide");$("#previewgenerated").modal("show")}}})}}else{$(".saveDraft").attr("title","A file is required to save draft. Please upload a file and try again!");$(".saveDraft").tooltip("show")}};e.saveModal=function(){n(function(){window.location.href=baseUrl+"dashboard"},3e3)};e.submitPost=function(n){if(e.post.title==""){$("#title").attr("title","The field cannot be blank");$("#title").addClass("error-class");return false}if(e.post.category==""){$("#title").attr("title","The field cannot be blank");$("#category").addClass("error-class");return false}if(e.post.impact==""){$("#title").attr("title","The field cannot be blank");$("#impact").addClass("error-class");return false}if(!e.isChanged){$(".upload").attr("title","The field cannot be blank");$("#title").addClass("error-class");$("#category").addClass("error-class");$("#impact").addClass("error-class");$("#hashtags").addClass("error-class");return false}if(e.isUploaded==true){if("video"==e.post.postType){e.files=document.getElementById("uploadVideoFile").files}e.post.hashtags=JSON.stringify(e.hashtags);var r=new FormData;angular.forEach(e.files,function(e){if(e!=null){r.append("files[]",e)}});if(null!=e.uploadedFiles){angular.forEach(e.uploadedFiles,function(e){if(e!=null){r.append("files[]",e)}})}e.isUploaded=true;e.post.location=e.location.lat+","+e.location.lng;if(e.post.location=="0,0"||e.post.location==""){$(".locationErr").focus();e.locationErr=true;return false}console.log(e.post);$("#waitingModal").modal("show");r.append("post",JSON.stringify(e.post));var i=baseUrl+"post/postSubmitted";var s=r;if(true==e.canSend){e.canSend=false;t.post(i,s,{transformRequest:angular.identity,headers:{"Content-type":undefined}}).success(function(e){if(e.success==true){window.location=baseUrl+"post?upload=success"}})}}else{}};e.getRecentPosts=function(){var n=baseUrl+"post/getRecentPosts?howMany=3";t.get(n).success(function(t){if(null!=t.data){e.recentPosts=t.data;for(var n=0;n<e.recentPosts.length;n++){if(e.recentPosts[n].postType=="Image"){e.showImage=true;e.recentPosts[n]["showImage"]=e.showImage;e.showVideo=false}else{e.showVideo=true;e.showImage=false;e.recentPosts[n]["showVideo"]=e.showVideo}}}else{e.empty=true}})};e.getRelatedPosts=function(n){var r=baseUrl+"post/getRelatedPosts?howMany=3&categId="+n;t.get(r).success(function(t){if(null!=t.data){e.relatedPosts=t.data}})};e.postId=$("#postId").val();e.location={lat:0,lng:0,address:""};e.removeHashtag=function(t){e.hashtags.splice(t,1)};e.getLocation=function(){if(navigator.geolocation){navigator.geolocation.getCurrentPosition(e.updatePosition,e.handleDenyLocation)}};e.handleDenyLocation=function(){e.locationErr=true};e.geocoder=new google.maps.Geocoder;e.updatePosition=function(t){e.post.location=t.coords.latitude+","+t.coords.longitude;e.location.lat=t.coords.latitude;e.location.lng=t.coords.longitude;var n=new google.maps.LatLng(e.location.lat,e.location.lng);e.geocoder.geocode({latLng:n},function(t,n){if(t&&t.length>0){e.locationErr=false;if(t.length>1){e.location.address=t[1].formatted_address}else{e.location.address=t[0].formatted_address}e.post.location=e.location}else{var r=e.location.lat+", "+e.location.lng;e.location.address="Unable to find address at given location. ("+r+")"}e.$apply();e.initMap();e.initCitySearch()});e.$apply()};e.changeLocation=function(){$("#locationModal").modal("show");setTimeout(function(){google.maps.event.trigger(e.map,"resize")},100)};e.map="";e.marker="";e.initMap=function(){var t={center:new google.maps.LatLng(e.location.lat,e.location.lng),zoom:16};e.map=new google.maps.Map(document.getElementById("map-canvas"),t);e.marker=new google.maps.Marker({map:e.map,draggable:true,animation:google.maps.Animation.DROP,position:new google.maps.LatLng(e.location.lat,e.location.lng)});google.maps.event.addListener(e.marker,"click",e.toggleBounce);google.maps.event.addListener(e.marker,"dragend",function(t){e.location.lat=t.latLng.lat();e.location.lng=t.latLng.lng();var n=t.latLng;var r=e.location.lat+", "+e.location.lng;e.post.position=r;e.geocoder.geocode({latLng:n},function(t,n){if(t&&t.length>0){e.location.address=t[0].formatted_address}else{e.location.address="Unable to find address at given location. ("+r+")"}e.$apply()})})};e.toggleBounce=function(){if(e.marker.getAnimation()!=null){e.marker.setAnimation(null)}else{e.marker.setAnimation(google.maps.Animation.BOUNCE)}};e.initCitySearch=function(){var t={};var n=document.getElementById("location-select-text");var r=new google.maps.places.Autocomplete(n,t);google.maps.event.addListener(r,"place_changed",function(){var t=r.getPlace();var n=t.geometry.location.lat();var i=t.geometry.location.lng();e.location.lat=n;e.location.lng=i;e.location.address=t.formatted_address;e.$apply()})};e.getRecentPosts();e.getLocation();e.initCitySearch();e.getRelatedPosts();if(null!=e.post.id||""!=e.post.id){e.getPost()}if(e.post.id==""){e.isChanged=false}else{e.isChanged=true}$(window).keydown(function(e){if(e.keyCode==13){e.preventDefault();return false}})}]
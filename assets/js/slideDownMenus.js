$(document).ready(function(e){var t=false;var n=false;var r=false;var i=false;$("#posts-menu").click(function(){if(t==true){$(".posts-option").slideUp("fast","swing");t=false}else{$(".posts-option").slideDown("fast","swing");t=true}});$("#settings-menu").click(function(){if(n==true){$(".settings-option").slideUp("fast","swing");n=false}else{$(".settings-option").slideDown("fast","swing");n=true}});$(document).mouseup(function(e){var s=$("#posts-menu");var o=$(".posts-option");if(!s.is(e.target)&&o.has(e.target).length===0&&r==false){$(".posts-option").slideUp("fast","swing");t=false}var u=$("#settings-menu");var a=$(".settings-option");if(!u.is(e.target)&&a.has(e.target).length===0&&i==false){$(".settings-option").slideUp("fast","swing");n=false}});if(window.location.href==baseUrl+"allPosts"){$(".posts-option").show();t=false;r=true;$("#allPosts").addClass("active")}else if(window.location.href==baseUrl+"post"){$(".posts-option").show();t=false;r=true;$("#addNew").addClass("active")}else if(window.location.href==baseUrl+"post?logged"){$(".posts-option").show();t=false;r=true;$("#addNew").addClass("active")}else if(window.location.href==baseUrl+"drafts"){$("#drafts").addClass("active")}else if(window.location.href==baseUrl+"profile"){$(".settings-option").show();n=false;i=true;$("#profile-tab").addClass("active")}else if(window.location.href==baseUrl+"newsRoom"){$("#newsRoom").addClass("active")}else if(window.location.href==baseUrl+"profile"){$("#newsRoom").addClass("active")}else if(window.location.href==baseUrl+"preferences"){$("#preferences").addClass("active")}})
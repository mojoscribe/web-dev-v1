// var CategoryCtrl=["$scope","$http",function(e,t){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();var n=window.location.search;e.categoriesData=[];e.getPostsByCategory=function(){var r=baseUrl+"category/getPosts"+n;t.get(r).success(function(t){console.log(t);if(t.data==null||t.data==""){e.empty=true}else{e.category=t.data.category;var n=processBreakingDate(t.data.data);e.categoryData=n;console.log(e.category);console.log(e.categoryData)}})};if(n){e.getPostsByCategory()}e.getCategoryPosts=function(){var n=baseUrl+"categoryPage/getPosts";t.get(n).success(function(t){console.log(t);if(t.data==null||t.data==""){e.empty=true}else{e.category=t.data.category;var n=processCategoryDate(t.data);console.log(n);e.categoriesData=n.data}})};e.right=function(e){var t=e.target;t=$(t);var n=t.prev(".bottom-slides").scrollLeft()+330;t.prev(".bottom-slides").stop().animate({scrollLeft:n},2e3)};e.left=function(e){var t=e.target;t=$(t);var n=t.next(".bottom-slides").scrollLeft()-330;t.next(".bottom-slides").stop().animate({scrollLeft:n},2e3)};e.getCategoryPosts()}]
var CategoryCtrl = ["$scope", "$http", function(e, t) {
    t.defaults.headers.common["XSRF_TOKEN"] = $("#csrf").val();
    var n = window.location.search;
    e.categoriesData = [];
    e.getPostsByCategory = function() {
        var r = baseUrl + "category/getPosts" + n;
        t.get(r).success(function(t) {
            console.log(t);
            if (t.data == null || t.data == "") {
                e.empty = true
                e.category = t.error.category;
            } else {
                e.category = t.data.category;
                var n = processBreakingDate(t.data.data);
                e.categoryData = n;
                console.log(e.category);
                console.log(e.categoryData)
            }
        })
    };

    e.getCategoryPosts = function() {
        var n = baseUrl + "categoryPage/getPosts";
        t.get(n).success(function(t) {
            console.log(t);
            if (t.data == null || t.data == "") {
                e.empty = true
            } else {
                e.category = t.data.category;
                var n = processCategoryDate(t.data);
                console.log(n);
                console.log(e.category);
                e.categoriesData = n.data
            }
        })
    };
    e.right = function(e) {
        var t = e.target;
        t = $(t);
        var n = t.prev(".bottom-slides").scrollLeft() + 330;
        t.prev(".bottom-slides").stop().animate({
            scrollLeft: n
        }, 2e3)
    };
    e.left = function(e) {
        var t = e.target;
        t = $(t);
        var n = t.next(".bottom-slides").scrollLeft() - 330;
        t.next(".bottom-slides").stop().animate({
            scrollLeft: n
        }, 2e3)
    };

    if (n) {
        e.getPostsByCategory();
    }else{
	    e.getCategoryPosts();
    }
}]
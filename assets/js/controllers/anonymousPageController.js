var AnonymousPageCtrl=["$scope","$http",function(e,t){e.anonNews=[];e.getNews=function(){var n=baseUrl+"page/anonymous/getPosts";t.get(n).success(function(t){var n=processBreakingDate(t.data.data);e.anonNews=n})};e.getNews()}]


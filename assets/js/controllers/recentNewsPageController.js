var RecentNewsPageCtrl=["$scope","$http",function(e,t){e.recentNews=[];e.getRecentNews=function(){var n=baseUrl+"recent/getPosts";t.get(n).success(function(t){console.log(t);var n=processBreakingDate(t.data.data);e.recentNews=n;console.log(e.recentNews)})};e.getRecentNews()}]
var HomeBreakingNewscontroller=["$scope","$http",function(e,t){t.defaults.headers.common["XSRF_TOKEN"]=$("#csrf").val();e.firstPost=[];e.otherPosts=[];e.empty=false;e.getBreakingPosts=function(){try{var n=baseUrl+"mainPage/getBreakingPosts";t.get(n).success(function(t){if(t.data.breakingNewsData==null){e.empty=true}else{console.log(t.data.breakingNewsData);res=processBreakingDate(t.data.breakingNewsData);e.firstPost=res[0];if(res[0].postType=="Image"){e.firstPost.showImage=true;e.firstPost.showVideo=false}else if(res[0].postType=="Video"){e.firstPost.showVideo=true;e.firstPost.showImage=false}for(var n=0;n<res.length-1;n++){e.otherPosts[n]=res[n+1];e.otherPosts[n].rank=n+2;if(res[n+1].postType=="Image"){e.otherPosts[n].showImage=true;e.otherPosts[n].showVideo=false}else if(res[n+1].postType=="Video"){e.otherPosts[n].showImage=false;e.otherPosts[n].showVideo=true}}}})}catch(r){showLoadingError("title","We are experiencing some trouble with the content you are trying to see. Please refresh your browser window once.")}};e.getBreakingPosts()}]
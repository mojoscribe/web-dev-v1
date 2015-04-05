'use strict'
var app = angular.module('myapp',['ngTable']);

app.controller('AnonymousCtrl', function($scope,$http, $filter, ngTableParams,$timeout) {
	$scope.posts = [];
	$scope.postId = "";
	$scope.single = [];
	$scope.categories = [];
	$scope.impacts = [];
	$scope.impact = "";
	$scope.category = "";

	$scope.categ = {
		postId:"",
		categId:"",
	}

	$scope.impac = {
		postId:"",
		impactId:"",
	}

	$scope.tableParams = new ngTableParams({
        page: 1,            // show first page
        count: 1000,          // count per page
        sorting: {
            serial: 'asc'
                 // initial sorting
        }
    }, {
        total: $scope.posts.length, // length of data
        getData: function($defer, params) {
            // use build-in angular filter
            var orderedData = params.sorting() ?
                                $filter('orderBy')($scope.posts, params.orderBy()) :
                                $scope.posts;

            $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
        }
    });


	$scope.getAllPosts = function(){
		var url = baseUrl + 'admin/anonymous/getAllPosts';
		$http.get(url).success(function(resp){
			$scope.posts = resp.data.posts;
			$scope.tableParams.reload();			
			$scope.categories = resp.data.categories;
			$scope.impacts = resp.data.impacts;
			
		});
	}

	// var updateTable = function(data){
	// 	$scope.tableParams = new ngTableParams({
	//         page: 1,            // show first page
	//         count: 10,          // count per page
	//         sorting: {
	//             serial: 'asc'
	//                  // initial sorting
	//         }
	//     }, {
	//         total: data.length, // length of data
	//         getData: function($defer, params) {
	//             // use build-in angular filter
	//             var orderedData = params.sorting() ?
	//                                 $filter('orderBy')(data, params.orderBy()) :
	//                                 $scope.posts;

	//             $defer.resolve(orderedData.slice((params.page() - 1) * params.count(), params.page() * params.count()));
	//         }
	//     });
	// }


	$scope.makeBreaking = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/makeBreaking';

		$http.post(url,$scope.postId).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.removeBreaking = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/removeBreaking';

		$http.post(url,$scope.postId).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.makeFeatured = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/makeFeatured';

		$http.post(url,$scope.postId).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.removeFeatured = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/removeFeatured';

		$http.post(url,$scope.postId).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.changeCategory = function(item){
		$scope.categ.postId = item.id;
		$scope.categ.categId = $scope.category;
		var url = baseUrl + 'admin/allPosts/changeCategory';

		$http.post(url,$scope.categ).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.changeImpact = function(item){
		$scope.impac.postId = item.id;
		$scope.impac.impactId = $scope.impact;
		var url = baseUrl + 'admin/allPosts/changeImpact';

		$http.post(url,$scope.impac).success(function(resp){
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.unpublish = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/unpublish';

		$http.post(url,$scope.postId).success(function(resp){
			console.log(resp);
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.publish = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/publish';

		$http.post(url,$scope.postId).success(function(resp){
			console.log(resp);
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.remove = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/remove';

		$http.post(url,$scope.postId).success(function(resp){
			console.log(resp);
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.approve = function(item){
		$scope.postId = item.id;
		var url = baseUrl + 'admin/allPosts/approve';

		$http.post(url,$scope.postId).success(function(resp){
			console.log(resp);
			if(resp.success == true){
				$scope.getAllPosts();
			}
		});
	}

	$scope.singlePost = function(item){
		$scope.single = item;
		$scope.impact = item.impact;
		$scope.category = item.category;
	}
	
	$scope.getAllPosts();
});
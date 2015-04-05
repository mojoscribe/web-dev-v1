var FeedbackCtrl = function($scope,$http){
	$scope.reply = '';
	$scope.showReply = false;

	$scope.email = $('#email').val();

	$scope.show = function(){
		$scope.showReply = true;
	}

	$scope.dismiss = function(){
		$scope.showReply = false;
	}

	$scope.replyEmail = function(){
		if('' != $scope.reply){
			var url = baseUrl + 'admin/feedback/reply';
			var data = [];
			data.push($scope.email);
			data.push($scope.reply);
			
			$http.post(url,data).success(function(response){
				console.log(response);
			});
		}else{
			$('#reply').addClass('error-class');
		}
	}
}
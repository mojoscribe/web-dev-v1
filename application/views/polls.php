<div class="container" ng-controller="PollPageCtrl">
	<div class="row">
		<div class="col-lg-12" >
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  POLLS</strong>
				</div>
			</div>
							
			<div class="col-lg-4 poll-column" ng-repeat="poll in polls">
				<div class="poll-question">
					{{poll.question}}
				</div>

				<div class="poll-options">
					<!-- <div class="answer" ng-repeat="answer in poll.answers track by $index">
						<input type="radio" name="poll-answer">
						{{answer.answer}}
					</div> -->
					<div class="poll-options" ng-hide="poll.isAnswered">
						<div class="option" ng-repeat="option in poll.answers track by $index">
							<input type="radio" name="poll-answer" id="option{{option.id}}" value="{{option.id}}" ng-change="pollOption(this)" ng-model="selectedOption">
							<label for="option{{option.id}}"> {{option.answer}} </label>
						</div>
					</div>
					<div class="poll-answers" ng-show="poll.isAnswered">
						<div class="option" ng-repeat="result in poll.results">
							<span class="option-text">{{result.option}} ({{result.count}})</span>
							<div class="option-bar">										
								<div class="perc" style="width: {{result.percentage}}%"></div>
							</div>
						</div>								
					</div>
				</div>
				<div class="btn btn-default single-poll-submit" ng-click="submitPoll(this)" id="{{poll.id}}" ng-hide="poll.isAnswered" style="text-align:center; font-size:14px;">Submit</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="denyMoadl" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
	    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			You have to be logged in to Submit poll.
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>
</div>
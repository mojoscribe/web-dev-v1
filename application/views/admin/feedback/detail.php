<style>
	.replyField{
		margin-top:20px;
	}

</style>

<div class="container" ng-controller="FeedbackCtrl">

	<div class="row">
			<div class="col-lg-12">
				<h2>&nbsp;</h2>
				<h2>
					Feedback
				</h2>

				<ul class="breadcrumb">
	                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
	                <li class="active">Feedback</li>
	            </ul>

			</div>
		</div>

	<div class="row">
		<div class="col-lg-2"></div>
		<div class="col-lg-8">
			<form role="form">
				<div class="form-group">
	                <label>Feedback from</label>
	                <p class="form-control" required="required" name="questionTitle">
	                	<?php  echo $feedback->getUser(); ?>
	                </p>
	                <input type="hidden" name="email" id="email" value="<?php echo $feedback->getUser(); ?>">
	          	</div>

	          	<div class="form-group">
	                <label>Content</label>
	                <p>
						<?php echo $feedback->getContent(); ?>
	                </p>
	          	</div>

			</form>
			<div class="pull-right reply-button" ng-hide="showReply">
				<button class="btn btn-primary" ng-click="show()">Reply</button>
			</div>

			<div class="row">
				<div class="col-lg-12 replyField" ng-show="showReply">
					<form action="" ng-submit="replyEmail()">
						Reply to "<b><?php echo $feedback->getUser(); ?></b>" : 
						<br>
						<textarea name="reply" ng-model="reply" id="" class="form-control" placeholder="Start typing here"></textarea>
					
						<div class="form-group" style="margin-top:10px;">
							<input type="submit" value="Reply" class="btn btn-primary">
							<img src="<?php echo base_url('assets/images/delete.png'); ?>" ng-click="dismiss()" style="cursor:pointer; height:30px; width:30px;" class="pull-right" alt="">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript" src="<?php echo base_url('assets/js/controllers/admin/feedbackcontroller.js'); ?>"></script>
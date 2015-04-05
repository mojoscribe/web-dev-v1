<div class="container" id="firstTimePage" ng-controller="FirstTimeCtrl">
	<div class="row">
		<div class="col-lg-12">
			<div class="block">
				<div class="message">
					<p>Hey <strong><?php echo $userData['userName']; ?></strong> !</p> You have been successfully registered!
					You have provided <strong><?php echo $userData['email']; ?></strong> as your Email Address. <br>Please check your registered email address for further instructions.
				</div>
					
				<input type="hidden" value="<?php echo $userData['id']; ?>" id="userId">
				<input type="hidden" value="<?php echo $userData['email']; ?>" id="userEmail">

				<div class="emailChange">
					<p ng-hide="textbox">If you want to change the given email address 
					<span class="emailOptions">
						<span id="link" ng-click="showInput()">Click here</span>
					</span>
					</p>

					<div ng-show="textbox">
						Email Address:
						<input type="email" class="form-control" ng-model="email" style="width:226px;" name="email" id="email" value="<?php echo $userData['email']; ?>" data-toggle="tooltip" data-placement="right">
						<span><img src="<?php echo base_url('assets/images/delete-icon.png'); ?>" style="height:20px; width:20px; cursor:hand;" ng-click="hideInput()" alt=""></span>
						<span id="emailSave"><img src="<?php echo base_url('assets/images/check.png'); ?>" style="height:30px; margin-top:-56px; margin-left:171px; width:31px; cursor:hand;" ng-click="changeEmail()" alt=""></span>	
					</div>
				</div>

				<div class="note">
					<strong>Note:</strong>
					<span>An email will be sent to the new e-mail address with a new verification link. Please check your email address for further instructions.</span>
				</div>
			</div>
		</div>
	</div>
</div>
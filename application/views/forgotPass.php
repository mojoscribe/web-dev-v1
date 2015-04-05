<div class="container" id="forgotPass" ng-controller="ForgotPassCtrl">
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-4">
				<input type="hidden" id="forgotVerify" value="<?php if(isset($_GET['verify']) && $_GET['verify'] == 'success'){ echo 1;} ?>">
				
				<form action="" ng-submit="submitEmail()" ng-hide="message">
					<div class="form-group">
						<h4>Enter your Registered Email Address : </h4>
						<input type="email" name="email" id="forgotPassEmail" class="form-control" ng-model="email" ng-blur="checkEmail()" data-toggle="tooltip" data-placement="right">
					</div>

					<div class="form-group" ng-hide="verified">
						<input type="submit" name="forgotPassSubmit" class="btn btn-default" value="Submit">
					</div>
				</form>

				<div class="passwords" ng-show="verified">
					<form action="" ng-submit="changePassword()">
						<div class="form-group">
							<h4>New Password : </h4>
							<input type="password" id="pass" ng-model="password" name="password" class="form-control">
						</div>

						<div class="form-group">
							<h4>Confirm Password : </h4>
							<input type="password" id="changePass" name="confirmPassword" class="form-control">
						</div>

						<div class="form-group">
							<input type="submit" name="changePassword" class="btn btn-default">
						</div>
					</form>
				</div>
			</div>
		</div>
		<div class="message" ng-show="message" style="font-size:18px; margin-top:40px; margin-bottom:40px;">
			An email has been sent to {{email}}. Please check your email address for further instructions.
		</div>

		<div class="message" ng-show="success" style="font-size:18px; margin-top:40px; margin-bottom:40px;">
			Your password has been successfully reset. Please follow this link to login : <a href="<?php echo base_url(''); ?>">Login</a>
		</div>
	</div>	
</div>
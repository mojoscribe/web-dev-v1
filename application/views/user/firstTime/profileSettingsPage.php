<div class="container" id="profile" ng-controller="SettingsPageCtrl">
	<div class="row">
		<form action="" ng-submit="submitInfo()">
		<div class="col-lg-12">
			<div class="col-lg-4 profile-picture">
				<div>
					<img class="img-circle" src="" ng-click="pictureIcon()" id="profile-picture-upload" style="margin-top:14px; height:210px; width:210px;" ng-mouseover="profilePictureHover()" ng-mouseout="profilePictureHover()"/>
					<input type="file" name="profilePicture" id="upload-profilePicture" style="display:none;" accept="image/*;capture=camera"  onchange="angular.element(this).scope().profilePicChanged(this)">
					<div class="profilePictureHover" ng-show="pictureHover">
						Upload Profile Picture
					</div>
				</div>
			</div>
			<div class="col-lg-8 profile-other-half">
				<div class="">
					<h3>Basic Information	</h3>
					<hr>
				</div>
				<form action="">
				<div class="reporter-handle" style="padding:0px;">
					<h4>Reporter Handle <span ng-show="!userNameAvailable" style="font-size:12px; color:#c50000;"> (Reporter Handle has already been taken)</span> 
						<span ng-show="rhEmpty" style="font-size:12px; color:#c50000;">  (Reporter Handle cannot be empty)  </span>
						<span ng-show="rhDisallowed" style="font-size:12px; color:#c50000;">  (You have entered disallowed characters in Reporter Handle. Please remove them)  </span>
						<span ng-show="insufLength" style="font-size:12px; color:#c50000;">  (Your Reporter Handle is insufficient in length. Minimum 6 characters are required)  </span>
					&nbsp;<img src="<?php echo base_url('assets/images/tick.png'); ?>" ng-show="userNameAvailable">
						
					</h4>
					<input type="hidden" name="userId" id="userId" ng-model="profileInformation.id" value="<?php echo $userData['id']; ?>">
					<input class="form-control" id="reporterHandle" ng-model="profileInformation.reporterHandle" ng-blur="checkUserName()" required type="text" name="reporterHandle" value="<?php echo $userData['userName']; ?>">
					<div class="help-tool"><p style="font-size:12px;"><b>Note:</b> This is your reporter handle. It will be your identity as a journalist on MoJoScribe, and will be used as a signature for all content that you upload. If you don't like your reporter handle, you have the freedom to chose a unique name now. You will not be able to chose another handle later. Once you do this, you will have officially become a Reporter on MoJoScribe. Happy Broadcasting!</p></div>
				</div>

				<div class="names">
					<div class="col-lg-6 halfening1">
						<h4 class="control-label">First Name</h4>
						<input type="text" class="form-control" ng-model="profileInformation.firstName" name="firstName" value="" placeholder="First Name" required>
					</div>

					<div class="col-lg-6 halfening2">
						<h4 class="control-label">Last Name</h4>
						<input type="text" class="form-control" ng-model="profileInformation.lastName" name="lastName" placeholder="Last Name" required>
					</div>
				</div>

				<div class="about">
					<h4>About</h4>
					<textarea class="form-control" type="text" ng-model="profileInformation.about" name="about" placeholder="Say a few words about yourself"></textarea>
				</div>

				<div class="contactNo">
					<h4>Contact No.</h4>
					<input class="form-control" type="tel" ng-model="profileInformation.contactNo" id="contactNo" name="contactNo" ng-keydown="checkNumber($event)" placeholder="000-000-0000">
				</div>

				<div class="gender col-lg-12">
					<h4>Gender</h4>
					<div class="form-group">
						<div class="female col-lg-3" style="height:auto; margin-bottom:40px;">
							<input type="radio" ng-model="female" ng-click="genderFemale()" name="gender" value="female">
							<div class="checkbox-label" style="margin-top:-34px;">
								Female
							</div>
						</div>
						<div class="male col-lg-3" style="height:auto; margin-bottom:40px;">
							<input type="radio" name="gender" value="male" ng-model="male" ng-click="genderMale()">
							<div class="checkbox-label" style="margin-top:-34px;">
								Male
							</div>
						</div>
					</div>
				</div>

				<div class="clearfix"></div>

				<div class="location">
					<div class=" halfening1 col-lg-6">
						<h4>Country</h4>
						<input class="form-control" type="text" id="country" name="country" placeholder="Country">
					</div>

					<div class=" halfening2 col-lg-6">
						<h4>City</h4>
						<input class="form-control" id="city" type="text" name="city" placeholder="City">
					</div>
				</div>

				<div class="profile-buttons">
					<a href="" class="btn btn-default half1 col-lg-6" style="margin-right:7px;">Reset Information</a>
					<input class="btn btn-default col-lg-6 half2" type="submit" value="Save Information"></input>	
				</div>
				</form>
			</div>
		</div>
		</form>
	</div>
</div>
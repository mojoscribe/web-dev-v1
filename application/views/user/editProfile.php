	<div class="col-lg-9" id="profile-edit">
	<div class="row" ng-controller="ProfileSettingCtrl">
		<form action="" ng-submit="submitInfo()">
			<input type="hidden" id="userId" value="<?php echo $profile['id']; ?>">
		<div class="col-lg-4 profile-picture">
			<img class="img-rect" src="<?php echo $profile['picture']; ?>" ng-click="pictureIcon()" ng-mouseover="profilePictureHover()" ng-mouseout="profilePictureHover()" id="profile-picture-upload" style="margin-top:14px; height:210px; width:210px;"/>
			<input type="file" name="profilePicture" id="upload-profilePicture" style="display:none;" accept="image/*;capture=camera"  onchange="angular.element(this).scope().profilePicChanged(this)">
			<div class="profilePictureHover" ng-show="pictureHover" style="left:62px;">Upload Profile Picture
			</div>
		</div>
		<div class="col-lg-8 profile-other-half">
			<div class="">
				<h3>Basic Information</h3>
				<hr>
			</div>
			
			<div class="reporter-handle">
				<h4>Reporter Handle</h4>
				<input class="form-control" type="text" placeholder="Handle" ng-model="profileInformation.reporterHandle" disabled></input>
			</div>

			<div class="names">
				<div class="col-lg-6 halfening1">
					<h4 class="control-label">First Name</h4>
					<input type="text" class="form-control" ng-model="profileInformation.firstName" placeholder="First Name">
				</div>

				<div class="col-lg-6 halfening2">
					<h4 class="control-label">Last Name</h4>
					<input type="text" class="form-control" ng-model="profileInformation.lastName" placeholder="Last Name">
				</div>
			</div>

			<div class="about">
				<h4>About</h4>
				<textarea class="form-control" type="text" ng-model="profileInformation.about"></textarea>
			</div>

			<div class="contactNo">
				<h4>Contact No.</h4>
				<input class="form-control" type="tel" ng-model="profileInformation.contactNo" name="contactNo" placeholder="000-000-0000">
			</div>

			<div class="gender col-lg-12">
				<h4>Gender</h4>
				<div class="form-group">
					<div class="female col-lg-3" style="height:auto; margin-bottom:40px;">
						<input type="radio" ng-model="profileInformation.gender" ng-click="genderFemale()" name="gender" value="female">
						<div class="checkbox-label" style="margin-top:-34px;">
							Female
						</div>
					</div>
					<div class="male col-lg-3" style="height:auto; margin-bottom:40px;">
						<input type="radio" name="gender" value="male" ng-model="profileInformation.gender" ng-click="genderMale()">
						<div class="checkbox-label" style="margin-top:-34px;">
							Male
						</div>
					</div>
				</div>
			</div>

			<div class="clearfix"></div>

			<div class="location" style="margin-bottom:20px;">
				<div class=" halfening1 col-lg-6">
					<h4>Country</h4>
					<input class="form-control" type="text" id="country" ng-model="profileInformation.country" value="{{profileInformation.country}}" placeholder="Country">
				</div>

				<div class=" halfening2 col-lg-6">
					<h4>City</h4>
					<input class="form-control" type="text" id="city" ng-model="profileInformation.city" value="{{profileInformation.city}}" placeholder="City">
				</div>
			</div>

			<div class="profile-buttons">
				<a href="" class="btn btn-default half1 col-lg-6" style="">Reset Information</a>
				<input class="btn btn-default col-lg-6 half2" type="submit" value="Save Information"></input>	
			</div>
			</form>
		</div>

		<div class="modal fade" id="save-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
		  	<div class="modal-dialog">
		    	<div class="modal-content">
		    		<div class="modal-header" style="padding:0px;">
		    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
		    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" style="margin-top:-10px;" alt="">
		    			</div>
		    		</div>
		      		<div class="modal-body" ng-controller="HeaderCtrl">
		      			<!-- <img src="<?php echo base_url('assets/images/loading.GIF'); ?>" alt="" style="width:20px; height:20px; margin-left:auto; margin-right:auto; display:block;"> -->
		      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
			      			Your Information has been saved.
		      			</div>
		      		</div>
		      	</div>
		    </div>
		</div>
	</div>
</div>
</div>
</div>
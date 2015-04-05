<div id="preferencesPage" class="container" style="margin-top:80px;">
	<div class="row" ng-controller="PreferencesPageCtrl">
		<div class="col-lg-12">
			<form action="" ng-submit="saveSettings()">
			<input type="hidden" name="userId" value="<?php echo $userData['id']; ?>">
			<div class="label">
				Categories of News you are interested in:
			</div>
			<input type="hidden" name="userId" id="userId" value="<?php echo $userData['id']; ?>">
			<hr>
			<div class="checkboxes">
				<div class="col-lg-3" ng-repeat="category in categories track by $index">
					<div class="form-group">
						<input type="checkbox" name="categoryName[{{category.id}}]" id="categoryName[{{category.id}}]" ng-checked="category.set" ng-click="saveCategory($index)" value="" class="category-checkbox">
						<label for="categoryName[{{category.id}}]">
							<div class="checkbox-label">
							{{category.name}}
							</div>
						</label>
					</div>
				</div>
			</div>

			<div class="label">
				News from Locations that you'll be interested in:
			</div>
			<hr>
			<div class="location-checkboxes col-lg-12" style="padding:0px;">
				
				<div class="form-group col-lg-3" style="padding:0px;">
					<input type="text" class="form-control" name="location-city" ng-blur="locationChanged" id="location-city">
				</div>
				<div class="col-lg-9">
					<div class="col-lg-4" ng-repeat="location in locations">
						<p style="padding:5px 20px;">
							<span><b>{{location}}</b></span>
							<span id="delete-icon" ng-click="removeLocation($index)"><img src="<?php echo base_url('assets/images/delete-icon.png'); ?>" style="height:20px; width:20px; cursor:hand;" alt=""></span>
						</p> 
					</div>
				</div>
			</div>

			<div class="label">
				Notification Setting:
			</div>
			<hr>
			<div class="notification-setting">
				<div class="form-group">
					<input type="checkbox" name="notification-mobile" ng-click="saveMobileNotification()" value="2" class="category-checkbox">
					<div class="checkbox-label">
						Mobile Notification
					</div>
				</div>
				<div class="form-group col-lg-6" style="padding:0px;">
					<input type="checkbox" name="notification-email" ng-click="saveEmailNotification()" value="1" class="category-checkbox">
					<div class="checkbox-label">
						Email Notification

						<span class="pull-right" ng-hide="hidePencil">
							@{{emailNotify.email}}
							&nbsp;
							<i class="glyphicon glyphicon-pencil edit" ng-click="editEmail()"></i>
						</span>

						<span class="pull-right" ng-show="hidePencil">

							Enter Email:
							<input type="email" name="email" class="form-control" ng-model="emailNotify.email">
							<span><img src="<?php echo base_url('assets/images/delete-icon.png'); ?>" style="height:20px; width:20px; cursor:hand;" ng-click="hideEmail()" alt=""></span>
							<span id="emailSave"><img src="<?php echo base_url('assets/images/check.png'); ?>" style="height:30px; margin-top:-56px; margin-left:171px; width:31px; cursor:hand;" ng-click="hideEmail()" alt=""></span>	
						</span>
					</div>
				</div>
			</div>

			<div class="saveSettings pull-right">
				<input type="submit" class="btn btn-default" value="Save Information">
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

<div class="col-lg-9" style="margin-bottom:10px;" ng-controller="PostCtrl">
	<form action="" id="post-upload-form" ng-submit="submitPost()" method="POST" enctype="multipart/form-data">
		<?php if(isset($_GET['upload'])){ ?>
			<?php if($_GET['upload'] == "success"){?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<span>Your Post was uploaded Successfully.</span>
				</div>
			<?php }elseif($_GET['upload'] == "v"){ ?>
				<div class="alert alert-success alert-dismissable">
					<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
					<span>Your Video was uploaded Successfully and is being processed.</span>
				</div>
			<?php	} ?>
		<?php } ?>
		<div class="uploadTabs">
			<div class="postAsYou" ng-class="{active: individual}" ng-click="indiv();">
				<div class="tabTitle">
					<i class="glyphicon glyphicon-user"></i>
					&nbsp;Post as Yourself
				</div>
			</div>
			<div class="postAnonymous" ng-class="{active: !individual}" ng-click="anonymous();">
				<div class="tabTitle">
					<img src="<?php echo base_url('assets/images/mask-anon.png'); ?>" alt="">
					&nbsp;Post Anonymously
				</div>
			</div>
		</div>
		<div id="upload-container">
			<div class="media" ng-hide="isUploaded">
				<div class="img-upload-btn" >
					<img ng-click="ImageIcon()" id="upload-image" class="upload-image-icon" style="width:100%;">
				</div>
				<div class="vid-upload-btn" >
					<video ng-click="VideoIcon()" class="upload-video-icon" id="upload-video" style="width:100%;"></video>
				</div>				
			</div>

			<div style="height:auto;" class="" ng-show="isImageUploaded" style="position:relative;">
				<img src="<?php echo base_url('assets/images/add-more.png'); ?>" ng-click="ImageIcon()" class="pull-right" data-toggle="tooltip" data-placement="left" title="Add more Images" style="cursor:hand; position:absolute; right:-18px; width:30px;" alt="">
					
				<!-- <img src="<?php echo base_url('assets/images/delete.png'); ?>" class="pull-right" ng-click="DismissUpload()" data-toggle="tooltip" data-placement="left" title="Dismiss and Add Video" style="cursor:hand; position:absolute; top:89px; right:-18px; width:30px;" alt=""> -->
				<div class="col-lg-12" style="padding:5px;" id="image-gallery">

				</div>
			</div>

			<div class="col-lg-12" id="video-gallery" style="height:auto; padding:5px;" ng-show="isVideoUploaded">
				<img src="<?php echo base_url('assets/images/delete.png'); ?>" class="pull-right" ng-click="DismissUpload()" style="cursor:pointer; position:absolute; top:1px; right:5px; width:25px;" alt="">
			</div>

			<div class="clearfix"></div>

			<div class="form-group">
				<input type="text" id="title" ng-model="post.title" ng-change="changed()" class="form-control upload" data-toggle="tooltip" data-placement="bottom" name="title" placeholder="Enter Post Title (Max 30 Words)" maxlength="240">
			</div>

			<div class="form-group">
				<textarea name="description" ng-model="post.description" id="description" name="description" class="form-control upload" placeholder="Enter Description (Max 200 words)" ng-change="changed()" maxlength="2000"></textarea>
			</div>

			<div class="clearfix"></div>

			<input type="file" name="images[]" onchange="angular.element(this).scope().ImagefilesChanged(this)" accept="image/*;capture=camera" style="display:none;" id="uploadImageFile" multiple>

			<input type="file" name="video" onchange="angular.element(this).scope().VideofilesChanged(this)" accept="video/*;capture=camcorder" style="display:none;" id="uploadVideoFile">

			<input type="hidden" name="postType" ng-model="post.postType" id="postType" value="{{post.postType}}">
			<input type="hidden" name="type" ng-model="post.type" value="{{type}}">

			<select class="form-control upload col-lg-6" ng-model="post.category" name="category" id="category" ng-change="changed();getRelatedPosts(post.category);" data-toggle="tooltip" data-placement="bottom">
				<option value=""> - Select Category - </option>
				<?php foreach ($categories as $categ): ?>
					<option value="<?php echo $categ->getId(); ?>"><?php echo $categ->getName(); ?></option>
				<?php endforeach ?>
			</select>

			<select class="form-control upload col-lg-6" ng-model="post.impact" name="impact" id="impact" ng-change="changed()" data-toggle="tooltip" data-placement="bottom">
				<option value=""> - Select Impact Level - </option>
				<?php foreach($impacts as $impact) { ?>
					<option value="<?php echo $impact->getId(); ?>"><?php echo $impact->getArea(); ?></option>
				<?php } ?>
			</select>
			<input type="hidden" name="id" ng-model="post.id" value="{{post.id}}">

			<div class="clearfix"></div>

			<div class="form-group" style="position:relative;">
				<input type="text" class="form-control upload-disabled" id="hash" data-toggle="tooltip" data-placement="bottom" title="blah" placeholder="You can enter a maximum of 10 tags." ng-keydown="pushHashtags($event)" ng-change="changed()" ng-model="hashtag">
				<!-- <img src="<?php echo base_url('assets/images/check.png'); ?>" style="height:40px; width:40px; position:absolute; top:6px; cursor:hand; right:10px;" ng-click="pushHashtags()" alt=""> -->
			</div>

			<div class="form-group">
				<!-- <textarea name="hashtags" class="form-control upload" ng-model="post.hashtags" id="hashtags" placeholder="Enter Hashtags here" ng-change="changed()" data-toggle="tooltip" data-placement="bottom"></textarea> -->
				<div id="hashtags" style="height:auto; background-color:#fff;">
					<span id="hashtag" ng-repeat="hashtag in hashtags track by $index|limitTo : 10">
						#{{hashtag}}
						<img src="<?php echo base_url('assets/images/delete.png'); ?>" ng-click="removeHashtag($index)" alt="">
					</span>
				</div>
			</div>
	
			<div class="form-group location-group">

				<div class="location-text">Event Location: </div>

				<div class="location-address">
					<div class="locationError" style="color:red; font-size:11px;" ng-show="locationErr">Sorry! Unable to fetch your location. Please enter your location by clicking on Change button.</div>
					<div class="address">{{location.address}}</div>
					<div ng-click="changeLocation()" class="btn btn-primary pull-right" style="width: 70px;padding: 10px; margin-top: -20px; background-color:#c50000; color:#fff; border-color:#fff;">Change</div>
				</div>				
				<div class="clearfix"></div>
			</div>

			<div class="post-source">
				<div class="post-source-checkbox">
					<input type="checkbox" name="post-source-checkbox" id="post-source-checkbox" class="post-source-checkbox" value="1" ng-click="selfSource()" checked>
					<div style="padding-left:30px; margin-top:-20px; font-size:14px; color:#555;">This video/image has been captured by me</div>
				</div>
			</div>

			<div class="form-group post-source-textarea" ng-show="showSourceArea">
				<input type="text" name="postSource" id="post-source" ng-model="post.source" ng-value="Self" class="form-control post-source-textarea" placeholder="Enter Source Here">
			</div>

		</div>

		<div class="upload-buttons col-lg-9 col-lg-offset-3" style="padding-right:0px;">
			<div class="upload-btn-group">
				<a class="btn btn-default col-lg-3 saveDraft" style="padding: 10px;" ng-click="autoSave('drafts')" data-toggle="tooltip" data-placement="bottom">Save to Drafts</a>
				<a class="btn btn-default col-lg-3 previewPost" ng-click="autoSave('previewPost')" id="previewPostbutton" href="">Preview Post</a>
				<a href="<?php echo base_url(''); ?>preview?id={{post.id}}" style="display:none;" id="previewLink123">1</a>
				<input type="submit" name="postSubmit" value="Post" class="btn btn-default col-lg-3 submitPost" id="post-submit" stlye="margin-top: 10px;">
			</div>
		</div>
	</form>

	<div class="clearfix"></div>

	<div class="modal fade" id="waitingModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="gif">
	      				<img src="<?php echo base_url('assets/images/loading.GIF'); ?>" style="width:40px; height:40px; margin-right:auto; margin-left:auto; display:block;" alt="">
	      			</div>
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			Please Wait while your post is being uploaded.
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>

	<div class="modal fade" id="messageModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
	    				<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			<p class="postPage-msg"><b>Hi Reporter</b>,
		      				<br>
		      				<br>

We are immensely proud of you for uploading your first News-worthy content.
We would like to take this moment to remind you to ensure that the content you are about to upload is in fact "News Worthy". To achieve this, we would like you to make sure that the content follows one or more of the following guidelines:
<br>
1. The content impacts a certain set of people, be it the local populace, national populace or global populace. 
<br>
2. The content is associated with another news that is already making headlines.
<br>
3. The content needs to be brought to the attention of the world.
<br>
4. The content is not a cat video!
<br>
<br>
We at MoJoScribe, trust your judgement on each of your uploads and do not apply any restrictions on your content. 

However, if your posts contain inappropriate content, or are flagged by other users of the site or are not "NEWS" per se(as defined by the criteria above), we reserve the right to remove the content permanently from the site and also ban any such user from further using this site. Having said that, we and the rest of the world are looking forward to any NEWS that you have to share with us. Spread the light!

<br>
<br>
Regards,
<br>
Team MojoScribe
<br>
</p>
	      			</div>
	      			<hr>

	      			<div class="checkbox-dismiss">
	      				<input type="checkbox" ng-click="messageSeen()">
	      				<span>Do not show this message again.</span>
	      			</div>

	      			<div class="okButton">
	      				<button class="btn btn default" data-dismiss="modal">Done</button>
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>

	<div class="modal fade" id="locationModal">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
	        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
	        <h4 class="modal-title">Change Location</h4>
	      </div>
	      <div class="modal-body">
	      	<!-- <div class="map-container"> 
	      		<div id="map-canvas"></div>	
	      	</div> -->
	        <p>Enter a location in the box to</p>
	        <div class="location-select">
	        	<div class="map-container" style="display: none;">
	        		<div id="map-canvas" style="width: 100%;"></div>		        				        	
	        	</div>
	        	<div class="col-lg-10 col-lg-offset-1">
	        		<input type="text" class="form-control" id="location-select-text">
	        	</div>
	        	<div class="clearfix"></div>
	        </div>
	      </div>
	      <div class="modal-footer">
	        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
	        <button type="button" class="btn btn-primary" style="background-color:#c50000; color:#fff; border-color:#fff;" data-dismiss="modal">Save changes</button>
	      </div>
	    </div><!-- /.modal-content -->
	  </div><!-- /.modal-dialog -->
	</div><!-- /.modal -->

	<div class="modal fade" id="previewModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" class="logo-header" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="gif">
	      				<img src="<?php echo base_url('assets/images/loading.GIF'); ?>" style="width:40px; height:40px; margin-right:auto; margin-left:auto; display:block;" alt="">
	      			</div>
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			Please wait while we generate a preview.
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>

	<div class="modal fade" id="savedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" class="logo-header">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			Your post has been saved as a draft.
	      			</div>
	      			<div class="gif">
	      				<img src="<?php echo base_url('assets/images/loading.GIF'); ?>" style="width:40px; height:40px; margin-right:auto; margin-left:auto; display:block;" alt="">
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>

	<div class="modal fade" id="postErrorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
		    		<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
	    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
		      			The File Type you are uploading is not allowed. Please click on the appropriate Icon.
	      			</div>
	      		</div>
	      	</div>
	    </div>
	</div>

	<div class="modal fade" id="previewgenerated" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog" style="width: 800px; height: auto;">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<!-- <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button> -->
	    			<!-- <img src="<?php echo base_url('assets/images/delete.png'); ?>" alt=""> -->
	    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
	    				<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="post">
						<div class="media popup-content-img" style="height: 400px;">
							<img src="{{file.image}}" ng-repeat="file in previewData.files" ng-show="showImage" alt="" ng-hide="mediaLength" style="margin-right:auto; height:400px; width:770px; margin-left:auto; display:block;">
							<ul class="preview-bxslider" ng-show="mediaLength">
								<li ng-repeat="file in previewData.files">
									<img ng-src="{{file.image}}" alt="" style="margin-right:auto; height:400px; width:750px; margin-left:auto; display:block;">
								</li>
							</ul>
						</div>
	
						<div class="post-details">
							<div class="post-title">
								{{previewData.title}}
							</div>
							<div class="post-author">
								<a href="<?php echo base_url(''); ?>{{previewData.author}}" style="color:#c50000;">{{previewData.author}}</a>
							</div>
							<div class="post-desc">
								{{previewData.description}}
							</div>
							
							<div class="post-tags">
								<ul id="tags">
									<li class="tag" ng-repeat="hashtag in previewData.hashtags track by $index">
										<a href="">#{{hashtag}}</a>
									</li>
								</ul>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>
	      		</div>
	      	</div>
	    </div>
	</div>	

<div class="row">
	<div class="recent-posts">
		<div class="breaking-news">
			<div class="breaking-news-title col-lg-10">
				<i class="glyphicon glyphicon-flash"></i><strong> RECENT POSTS</strong>
			</div>
		</div>
		<div class="clearfix"></div>
		<div>
			<div class="recent-posts" ng-repeat="recentPost in recentPosts" style="width: 259px; overflow: hidden; margin: 0px 2px; float: left;" > 
				<div class="recentPosts-media">
					<a href="<?php echo base_url(''); ?>single/{{recentPost.slug}}">
						<img ng-src="<?php echo base_url(''); ?>{{recentPost.file}}" style="display:block; width:259px; height:179px; margin-left:auto; margin-right:auto;">						
						<div class="videoCamera" ng-show="recentPost.showVideo" style="position:relative; top:-176px;">
							<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
						</div>
					</a>
				</div>
				<div class="posts-description text-over">
					<a href="<?php echo base_url(); ?>single/{{recentPost.slug}}" class="title-link"><h4 class="text-over"><strong>{{recentPost.title}}</strong></h4></a>
					<span ng-repeat="hashtag in recentPost.hashtags track by $index"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hashtag}}</a> &nbsp;</span>&nbsp;
					<div class="posts-date">
						<span class="pull-right"><strong>{{recentPost.date}}</strong></span>
					</div>
				</div>
			</div>
		</div>
		<div class="clearfix"></div>
	</div>
</div>
	
<!-- <div class="row">
	<div class="breaking-news">
		<div class="breaking-news-title col-lg-10">
			<i class="glyphicon glyphicon-flash"></i><strong> RELATED POSTS</strong>
		</div>
	</div>
	<div class="related-news1">
		<div class="col-lg-7"></div>
		<div class="clearfix"></div>
		<div class="posts" ng-repeat="post in relatedPosts" style="width: 259px; overflow: hidden; margin: 0px 2px; float: left;">
			<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
				<img ng-src="{{post.file}}" height="165" width="250" style="display:block; margin-left:auto; margin-right:auto;">
			</a>
			<div class="posts-description">
				<h4><strong>{{post.title}}</strong></h4> 
				<div class="related-desc">
					{{post.description}}
				</div>

			</div>
			<!-- <div class="posts-date">
				<h5 class="pull-right"><strong>Date Posted</strong></h5>
			</div> -->
	<!---	</div>

	</div>
</div> -->


</div>
<div class="clear"></div>
</div> <!-- .row from sidebar.php -->


</div> <!-- .container from sidebar.php -->

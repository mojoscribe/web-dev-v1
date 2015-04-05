<div ng-controller="PostCtrl">	
	<form action="" ng-submit="submitPost()" id="post-upload-form" method="POST" enctype="multipart/form-data">
	<div class="col-lg-9" >
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
			<input type="hidden" name="postId" id="postId" value="<?php echo $draftId; ?>">
			<div class="col-lg-12" style="padding:5px;" id="previous-image-gallery" ng-show="isImageUploaded">
				<div ng-repeat="file in uploadedFiles track by $index" class="previousImgParent">
					<img src="{{file.file}}" class="previousImg">
					<img src="<?php echo base_url('assets/images/delete.png'); ?>" ng-click="removeImage(this)" class="previousDeleteImg" id="{{file.serial}}">
				</div>
			</div>

			<div class="previous-video-gallery" class="col-lg-12" style="padding:5px;" ng-show="videoThere">
				<video src="{{file.file}}" style="height:250px; padding:5px; width:100%;" class="previousVideo" controls ng-repeat="file in uploadedFiles track by $index"></video>
				<img src="<?php echo base_url('assets/images/delete.png'); ?>" ng-click="removeVideo(this)" class="previousDeleteVideo" id="{{file.serial}}">
			</div>
			<div class="media" ng-hide="isUploaded">
				<div class="image-container col-lg-6" style="height:auto;">
					<img ng-click="ImageIcon()" id="upload-image" class="upload-image-icon" style="width:100%;">
				</div>

				<div class="video-container col-lg-6" style="height:auto;">
					<video ng-click="VideoIcon()" class="upload-video-icon" id="upload-video" style="width:100%;"></video>
				</div>
			</div>



			<div style="height:auto;" class="" ng-show="isImageUploaded" ng-hide="!isImageUploaded" style="position:relative;">
				<img src="<?php echo base_url('assets/images/add-more.png'); ?>" ng-click="ImageIcon()" class="pull-right" data-toggle="tooltip" data-placement="left" title="Add more Images" style="cursor:hand; position:absolute; right:-18px; width:30px;" alt="">
					
				<!-- <img src="<?php echo base_url('assets/images/delete.png'); ?>" class="pull-right" ng-click="DismissUpload()" data-toggle="tooltip" data-placement="left" title="Dismiss and Add Video" style="cursor:hand; position:absolute; top:89px; right:-18px; width:30px;" alt=""> -->
				<div class="col-lg-12" style="padding:5px;" id="image-gallery">
					
				</div>
			</div>

			<div class="col-lg-12" id="video-gallery" style="height:auto; padding:5px;" ng-hide="videoThere" ng-show="isVideoUploaded">
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
				<input type="text" class="form-control upload-disabled" id="hash" placeholder="Seperate tags using space" ng-keydown="pushHashtags($event)" ng-model="hashtag">
				<!-- <img src="<?php echo base_url('assets/images/check.png'); ?>" style="height:40px; width:40px; position:absolute; top:6px; cursor:hand; right:10px;" ng-click="pushHashtags()" alt=""> -->
			</div>

			<div class="form-group">
				<!-- <textarea name="hashtags" class="form-control upload" ng-model="post.hashtags" id="hashtags" placeholder="Enter Hashtags here" ng-change="changed()" data-toggle="tooltip" data-placement="bottom"></textarea> -->
				<div id="hashtags" style="height:150px; background-color:#fff;">
					<span id="hashtag" ng-repeat="hashtag in hashtags track by $index">
						#{{hashtag}}
						<img src="<?php echo base_url('assets/images/delete.png'); ?>" ng-click="removeHashtag($index)" alt="">
					</span>
				</div>
			</div>
	
			<div class="form-group location-group">
				<div class="location-text">Event Location: </div>
				<div class="location-address">
					<div class="address">{{location.address}}</div>
					<div ng-click="changeLocation()" class="btn btn-primary" style="margin-left: 10px; float:right; margin-top: -15px; background-color:#c50000; color:#fff; border-color:#fff;">Change</div>
				</div>
				<div class="clearfix"></div>
			</div>

			<div class="post-source">
				<div class="post-source-checkbox">
					<input type="checkbox" name="post-source-checkbox" id="post-source-checkbox" class="post-source-checkbox" value="1" ng-click="selfSource()" checked>
					<div style="padding-left:30px; margin-top:-22px; font-size:15px; color:#555;">This video/image has been shot by me</div>
				</div>
			</div>

			<div class="form-group post-source-textarea" ng-show="showSourceArea">
				<textarea name="postSource" id="post-source" ng-model="post.source" value="" class="form-control post-source-textarea" placeholder="Enter Source Here"></textarea>
			</div>

		</div>

		<div class="upload-buttons col-lg-9 col-lg-offset-3" style="padding-right:0px;">
			<div class="upload-btn-group">
				<a class="btn btn-default col-lg-3 saveDraft" style="padding: 10px;" ng-click="autoSave('drafts')" data-toggle="tooltip" data-placement="bottom">Save to Drafts</a>
				<a class="btn btn-default col-lg-3 previewPost" ng-click="autoSave('previewPost')" id="previewPostbutton" href="">Preview Post</a>
				<!-- <a href="<?php echo base_url(''); ?>preview?id={{post.id}}" style="display:none;" id="previewLink123">1</a> -->
				<input type="submit" name="postSubmit" value="Post" class="btn btn-default col-lg-3 submitPost" id="post-submit" stlye="margin-top: 10px;">
			</div>
		</div>
		<div class="clearfix"></div>
		<div class="row">
			<div class="recent-posts">
				<div class="breaking-news">
					<div class="breaking-news-title col-lg-10">
						<i class="glyphicon glyphicon-flash"></i><strong> RECENT POSTS</strong>
					</div>
				</div>
				<div class="clearfix"></div>
				<div>
					<div class="posts" ng-repeat="recentPost in recentPosts" style="width: 259px; overflow: hidden; margin: 0px 2px; float: left;" > 
						<div class="recentPosts-media">
							<a href="<?php echo base_url(''); ?>single/{{recentPost.slug}}">
								<img ng-src="<?php echo base_url(''); ?>{{recentPost.file}}" style="width:259px; height:179px; display:block; margin-left:auto; margin-right:auto;">						
								<div class="videoCamera" ng-show="recentPost.showVideo" style="position:relative; top:-176px;">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>
							</a>
						</div>
						<div class="posts-description text-over">
							<a href="<?php echo base_url(); ?>post/detail?id={{recentPost.id}}" class="title-link"><h4 class="text-over"><strong>{{recentPost.title}}</strong></h4></a>
							<span ng-repeat="hashtag in recentPost.hashtags track by $index"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hashtag}} </a>&nbsp;</span>&nbsp;
							<div class="posts-date">
								<h5 class="pull-right"><strong>{{recentPost.date}}</strong></h5>
							</div>
						</div>
					</div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
		<br>
			
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
			<!-- 	</div>

			</div>
		</div> -->
	</div> 
	<!-- <div class="clearfix"></div> -->
	
	</form>

	<div class="modal fade" id="waitingModal" data-keyboard="false" data-backdrop="static" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
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

	<div class="modal fade" id="savedModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" class="logo-header">
	    				<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
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

	<div class="modal fade" id="locationModal">
	  <div class="modal-dialog">
	    <div class="modal-content">
	      <div class="modal-header">
		    <img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
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
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
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

	<div class="modal fade bs-example-modal-lg" id="previewgenerated" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
	  	<div class="modal-dialog">
	    	<div class="modal-content" style="width:940px; margin-left:-180px; height:600px;">
	    		<div class="modal-header" style="padding:0px;">
	    			<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" style="margin-top:-10px;">
		    			<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">

	    			</div>
	    		</div>
	      		<div class="modal-body">
	      			<div class="post">
						<div class="media" style="height: 400px;">
							<img src="<?php echo base_url(''); ?>{{file.image}}" ng-repeat="file in previewData.files" ng-show="showImage" alt="" ng-hide="mediaLength" style="margin-right:auto; height:400px; width:770px; margin-left:auto; display:block;">
							<ul class="preview-bxslider" ng-show="mediaLength">
								<li ng-repeat="file in previewData.files">
									<img src="<?php echo base_url(''); ?>{{file.image}}" alt="" style="margin-right:auto; height:400px; width:770px; margin-left:auto; display:block;">
								</li>
							</ul>
							<video ng-hide="showImage" id="videoId" class="video-js vjs-default-skin vjs-big-play-centered"
								controls preload="auto" width="100%" height="400"
								poster=""
								data-setup='{"example_option":true}'>
								<source src="{{file.image}}" type="video/webm">
								<source src="{{file.image}}" type="video/ogg">
								<source src="{{file.image}}" type="video/mp4">
							</video>
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
										<a href="">#{{hashtag}} </a>
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
</div>
</div>

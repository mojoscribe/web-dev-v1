
	<div class="col-lg-9" ng-controller="PostListCtrl">
		<div class="drafts-button-panel">
			<a class="btn btn-default col-lg-3 delete-selected" ng-click="deletePosts()"><i class="glyphicon glyphicon-trash"></i> Delete Selected</a>
			<a class="btn btn-default col-lg-3 publish-selected" ng-click="unpublishPosts()"><i class="glyphicon glyphicon-eye-close"></i> Unpublish Selected</a>
			<select class="pull-right drafts-page" ng-change="getAll()" ng-model="selectBox">
				<option value="5">Show 5 Posts</option>
				<option value="10">Show 10 Posts</option>
				<option value="20">Show 20 Posts</option>
				<option value="50">Show 50 Posts</option>
				<option value="100">Show 100 Posts</option>
			</select>
		</div>

		<div class="clearfix"></div>

		<div id="all-posts-news-container">
			<div class="no-posts-message" ng-show="empty" style="text-align:center;">
				<h3>There are no posts to display for this user.</h3>
			</div>
			<div class="col-lg-6 all-posts-news-item" style="height: 315px;padding: 0px;padding-left: 14px;" ng-repeat="post in posts">
				<div class="all-posts-image" style="height:175px;">
					<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
						<img class="all-posts-news-image" src="{{post.file}}" style="display:block; margin-left:auto; margin-right:auto;">
						<div class="videoCamera" ng-show="post.showVideo" style="left:16px;">
							<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
						</div>
					</a>
				</div>
	
				<div class="all-posts-news-content" style="width:100%; height:105px;">
					<div class="all-posts-news-title">
						<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link"><h4>{{post.title}}</h4></a>
						<p class="all-posts-news-date">{{post.date}}</p>
					</div>

					<div class="all-posts-news-tags">
						<span ng-repeat="hash in post.hashtags track by $index"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hash}}</a> &nbsp; </span>
					</div>
				</div>
				<div class="all-posts-news-rating" style="height:35px;">
					<img src="<?php echo base_url('assets/images/rating-icon.png'); ?>" style="height:25px;">  
					<span>{{post.rating}}</span>
					<input type="checkbox" ng-model="checked[$index]" class="all-posts-checkbox pull-right" name="all-posts-news-checkbox">		
				</div>
			</div>

		</div>
	</div>
</div>
</div>

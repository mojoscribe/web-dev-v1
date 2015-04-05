<div class="container page-container">
	<div class="row" ng-cloak ng-controller="CategoryCtrl">
		<div class="col-lg-12" >
			<h3>Categories</h3>
			<hr>
			<div class="col-lg-12" style=" padding:0px;" ng-repeat="category in categoriesData">
				<div class="breaking-news" style="margin-top: 6px;">
					<div class="breaking-news-title col-lg-10">
						<i class="glyphicon glyphicon-flash"></i><strong>  {{category.name}}</strong>
					</div>
				</div>
				<div class="buttons left leftButton" style="position:absolute; left:-40px; top:180px;" ng-click="left($event)"> </div> 
				<div class="bottom-slides" id="bottom-slides" style="width: 100%; min-height:315px;">
					<div class="bottom-slide">					
					
						<div class="bottom-slider-post col-lg-3" ng-repeat="post in category.posts">
							<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
								<div class="videoCamera" ng-show="post.showVideo">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>
								<div class="bottom-post-image">
									<img src="{{post.files}}" alt="">
								</div>
							</a>
							<div class="bottom-content" style="min-height:150px;">
								<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link">{{post.headline}}</a>
								<p style="font-size:12px;">By : <a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">{{post.author}}</a> | 
								{{post.date}}</p>
								<div class="text-over" style="font-size:12px;">{{post.description}}</div>
<br>
								<div class="all-posts-news-tags text-over" style="margin-bottom:20px; font-size:12px;">
									<span ng-repeat="hashtag in post.hashtags"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hashtag}}</a></span>&nbsp;
								</div>
							</div>
						</div>
					</div>

				</div>
				<div class="buttons right rightButton" style="position:absolute; right:-40px; top:180px;" id="right" ng-click="right($event)"> </div>
				<br>
			</div>
		</div>
	</div>
</div>
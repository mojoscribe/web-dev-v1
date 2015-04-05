<div class="container page-container" ng-controller="FeaturedCtrl">
	<div class="row" ng-cloak style="margin:0px;margin-bottom:10px;">
		<div class="col-lg-12 mojo-pics-cont" >
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  MOJOPICKS</strong>
				</div>
			</div>
			<div class="inner-div">
				<div class="col-lg-4 featured-post" ng-repeat="post in posts">
					<div class="all-posts-image" style="height:175px;">
						<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
							<img class="all-posts-news-image" src="{{post.files[0].thumb}}" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">						
							<div class="videoCamera" ng-show="post.showVideo">
								<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
							</div>
						</a>
					</div>

					<div class="all-posts-news-content" style="width:100%;">
						<div class="all-posts-news-title text-over">
							<a href="<?php echo base_url(); ?>single/{{post.title}}" class="title-link text-over">{{post.title}}</a>
							<p style="font-size:12px;">By: <a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">{{post.author}}</a> | {{post.date}}</p>
							<div class="text-over" style="font-size:12px;">{{post.description}}</div>
						</div>
<br>
						<div class="all-posts-news-tags text-over" style="margin-bottom:20px;">
							<span ng-repeat="hashtag in post.hashtags track by $index" style="font-size:12px;"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hashtag}}</a>  </span>&nbsp;
						</div>
					</div>
				</div>
				<div ng-hide="posts">
					<p style="text-align:center; font-size:20px;">There are no MojoPicks right now</p>
				</div>
			</div>
		</div>
	</div>
</div>

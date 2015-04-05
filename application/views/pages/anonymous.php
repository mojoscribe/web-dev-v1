<div class="container">
	<div class="row" ng-controller="AnonymousPageCtrl">		
		<div class="col-lg-12" id="anonymous-posts">
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  ANONYMOUS</strong>
				</div>
			</div>
			<div class="inner-div">
				<div class="col-lg-4 featured-post" ng-repeat="post in anonNews">
					<div class="all-posts-image" style="height:175px;">
						<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
							<img class="all-posts-news-image" src="{{post.files}}" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">						
							
							<div class="videoCamera" ng-show="post.showVideo">
								<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
							</div>
							
						</a>
					</div>

					<div class="all-posts-news-content" style="width:100%; height:auto;">
						<div class="all-posts-news-title text-over">
							<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link">{{post.title}}</a>
							<p style="font-size:12px;">By: <a href="<?php echo base_url('Anonymous'); ?>" class="author-name-link">Anonymous</a> | {{post.date}}</p>
							<div class="text-over" style="font-size:12px;">{{post.description}}</div>
						</div>
						<br>
						<div class="all-posts-news-tags text-over" style="margin-bottom:20px; font-size:12px;">
							<span ng-repeat="hashtag in post.hashtags track by $index"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link">#{{hashtag}}</a></span>&nbsp;
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

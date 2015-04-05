<div class="container">
	<div class="row" ng-controller="CategoryCtrl">
		<div class="col-lg-12" >
			<div class="breaking-news" style="margin-top: 10px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  {{category}}</strong>
				</div>
			</div>

			<div class="col-lg-4" ng-repeat="post in categoryData" style="height:auto; padding:0px; width:322px; margin-right:15px; margin-top:10px; margin-bottom:10px; border-bottom:2px solid #E4495B;">
				<div class="all-posts-image" style="height:175px;">
					<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
						<img class="all-posts-news-image" src="{{post.files}}" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">
						<div class="videoCamera" ng-show="post.showVideo">
							<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
						</div>
					</a>
				</div>

				<div class="all-posts-news-content" style="width:100%; height:auto;">
					<div class="all-posts-news-title text-overflow">
						<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link">
							{{post.title}}
						</a>
						
						<p style="font-size:12px;">By: <a href="<?php echo base_url(); ?>{{post.author}}" class="author-name-link"> {{post.author}}</a> | {{post.date}}</p>
						<div class="text-over" style="font-size:12px;">{{post.description}}</div>
					</div>
					<br>

					<div class="all-posts-news-tags text-over" style="margin-bottom:20px; font-size:12px;">
						<span ng-repeat="hashtag in post.hashtags"><a href="<?php echo base_url(); ?>search?q={{hashtag}}" class="hashtag-link"> # {{hashtag}} </a></span> &nbsp;
						&nbsp;
					</div>

					<div class="categ-impact">
					</div>
				</div>
			</div>
			<div class="no-posts" ng-show="empty">
				<h3>There are no posts available for this category</h3>
			</div>
		</div>
	</div>
</div>

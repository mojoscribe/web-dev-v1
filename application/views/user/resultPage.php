<div class="container">
	<div class="row" ng-controller="SearchCtrl">
		<div class="col-lg-12" id="searchPage">

			<div class="search-results col-lg-6" style="height:auto; width:523px; margin-bottom:20px;" ng-repeat="post in searchResults">
				<div class="all-posts-image" style="height:175px;">
					<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
						<img class="all-posts-news-image" src="{{post.files}}" height="175" width="400"  style="display:block; margin-left:auto; margin-right:auto;">
						<div class="videoCamera" style="left:16px;" ng-show="post.showVideo">
							<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
						</div>
					</a>
				</div>

				<div class="all-posts-news-content text-overflow" style="width:100%; height:auto; padding-bottom: 10px;">
					<div class="all-posts-news-title text-overflow">
						<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="text-over"><h4>{{post.title}}</h4></a>
						<a href="<?php echo base_url(''); ?>{{post.author}}"><div>{{post.author}}</div></a>
						<div class="text-over">{{post.description}}</div>
						<br>
						<p class="all-posts-news-date">
							{{post.date}}
						</p>
					</div>

					<div class="all-posts-news-tags text-over">
						<span ng-repeat="hashtag in post.hashtags">#{{hashtag}}   </span>&nbsp;
						&nbsp;
					</div>
				</div>
			</div>
			<div class="no-posts-message"  style="height:300px;; font-size:18px; margin-top:15px;" ng-show="empty">
				Your search - <strong>"{{q}}"</strong> - did not match any documents.
				<br>
				Suggestions:
				<br><br>
				Make sure that all words are spelled correctly.<br>
				Try different keywords.<br>
				Try more general keywords.<br>
				Try fewer keywords.<br>
			</div>
		</div>
	</div>
</div>

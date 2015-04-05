<div class="container" ng-cloak>
	<div class="row" ng-controller="RecentNewsPageCtrl">
		<div class="col-lg-12">
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  RECENT NEWS</strong>
				</div>
			</div>
			<div class="inner-div">
		<?php //if(is_array($recentNews)){ 

			// foreach ($recentNews as $recent) {?>
				<div class="col-lg-4 featured-post" ng-repeat="recent in recentNews">
					<div class="all-posts-image" style="height:175px;">
						<a href="<?php echo base_url(''); ?>single/{{recent.slug}}">
							<img class="all-posts-news-image" src="{{recent.files}}" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">
							<div class="videoCamera" style="top:11px; left:12px;" ng-show="recent.showVideo">
								<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
							</div>
						</a>
					</div>

					<div class="recent-posts-news-content" style="background-color:#fff; min-height:141px;">
						<div class="all-posts-news-title" style="padding:8px;">
							<a href="<?php echo base_url(''); ?>single/{{recent.slug}}" class="text-over title-link">{{recent.title}}</a>
							<p style="font-size:12px;">By : <a href="{{recent.author}}" class="author-name-link">{{recent.author}}</a> | {{recent.date}}</p>
							<div class="text-over" style="font-size:12px;">{{recent.description}}</div>
						</div>

						<div class="all-posts-news-tags text-over" style="background-color:#fff; padding:8px; font-size:12px;">

							<a href="<?php echo base_url(''); ?>search?q={{hashtag}}" class="hashtag-link" ng-repeat="hashtag in recent.hashtags"><span>#{{hashtag}}</span></a>&nbsp;

						</div>
					</div>
				</div>
		<?php //}?>
		<?php //} ?>
			</div>
		</div>
	</div>	
</div>
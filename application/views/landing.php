<div id="home-top">
	<div class="container">	

		<div class="row">
			<div class="col-lg-12">
				<div class="clearfix"></div>
				<a href="<?php echo base_url('trending'); ?>">
				<div class="breaking-news" id="trendingNewsContainer">
					<div class="breaking-news-title col-lg-10">
						<i class="glyphicon glyphicon-flash"></i><strong>  TRENDING NEWS</strong>
					</div>
				</div>
				</a>
				<!-- For slider -->
				<div class="recent-news" ng-controller="TrendingNewsCtrl">
					<div class="upper-grid">
						<div class="grid-left">
							<div ng-repeat="post in trendingNewsPostsLeft" class="grid-box-{{$index+1}} fadeOutAnim fadeInAnim">
								<a href="<?php echo base_url('single/{{post.slug}}'); ?>">
									<img src="{{post.files[0].small}}">
									<div class="videoCamera" ng-show="post.showVideo">
										<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
									</div>
								</a>

								<div class="overlayImage text-overflow">
									<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link">
										<span class="trending-meta">
											{{post.title}}
										</span>
									</a>
									<br>
									<a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">
										<span class="title trending-meta" style="float:right;">
											{{post.author}}
										</span>
									</a>

									<span class="trending-meta date-tags" style="float:left;">
										{{post.date}}	
									</span>
								</div>
							</div>
						</div>
						
						<div class="grid-right">
							<div ng-repeat="post in trendingNewsPostsRight | limitTo : 3" class="grid-box-{{$index+5}} fadeOutAnim fadeInAnim">
								<a href="<?php echo base_url('single/{{post.slug}}'); ?>">
									<img src="{{post.files[0].thumb}}">
									<div class="videoCamera" ng-show="post.showVideo">
										<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
									</div>
								</a>

								<div class="overlayImage text-overflow">
									<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link">
										<span class="trending-meta">
											{{post.title}}
										</span>
									</a>
									<br>
									<a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">
										<span class="title trending-meta" style="float:right;">
											{{post.author}}
										</span>
									</a>


									<span class="trending-meta date-tags" style="float:left;">
										{{post.date}}	
									</span>
								</div>
							</div>
						</div>
					</div>					
				</div>
				
			</div>
		</div>
		<div class="row">
			<div id="breakingNewsContainer"></div>
			<div class="col-lg-12">
				<!-- col-lg-12 end -->
				<div ng-controller="HomeBreakingNewscontroller" style="height:620px;">
					<a href="<?php echo base_url(''); ?>page/breaking" class="dashboard-categ-link">
						<div class="breaking-news" id="breaking-scroll-point">
							<div class="breaking-news-title col-lg-10">
								<i class="glyphicon glyphicon-flash"></i><strong>  BREAKING NEWS</strong>
							</div>
						</div>
					</a>
					<div class="clearfix"></div>
					<div class="breakingVideoCamera" ng-show="firstPost.showVideo">
						<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
					</div>
					<div class="col-lg-5 breaking-1">
						<a href="<?php echo base_url(''); ?>single/{{firstPost.slug}}" style="text-decoration:none;">
							<img src="{{firstPost.files[0].long }}" style="height:464px; width:100%;">

						</a>
						
						<div class="breaking-1-description">
							<div class="breaking-rank col-lg-1">
								<div class="arrow-temp">
									<p>
										1
										<img src="<?php echo base_url();?>assets/images/arrow-right.png" width="8"/>
									</p>
								</div>
							</div>

							<div class="desc-content col-lg-10">
								<a href="<?php echo base_url(''); ?>single/{{firstPost.slug}}" class="title-link">
									<h5 class="desc-post-title">{{firstPost.title}}</h5>
								</a>
								<p style="font-size:12px;">By: 
									<a href="<?php echo base_url(''); ?>{{firstPost.author}}" style="text-decoration:none; color:#c50000;">{{firstPost.author}}</a> | {{firstPost.date}}</p>
								<p class="desc-post-description" style="font-size:12px;"> {{firstPost.description}}</p>
							</div>
						</div>
					</div>

					<div class="col-lg-7 other-4-breaking-container">
						
						<div class="breaking col-lg-6" ng-repeat="otherPost in otherPosts | limitTo : 4">
							<div class="breakVideoCamera" ng-show="otherPost.showVideo">
							</div>
							<a href="<?php echo base_url(''); ?>single/{{otherPost.slug}}">
								<img src="{{otherPost.files[0].thumb}}" style="width:100%; height:176px;">
							</a>

							
							<div class="breaking-description">
								<div class="breaking1-rank col-lg-2">
									
									<div class="arrow-temp">
										<p>
											{{otherPost.rank}}
											<img src="<?php echo base_url();?>assets/images/arrow-right.png" width="8"/><!-- <i class="glyphicon glyphicon-chevron-right"></i> -->
										</p>
									</div>
								</div>
							    <div class="desc-content col-lg-10">
							        <a href="<?php echo base_url(''); ?>single/{{otherPost.slug}}" style="text-decoration:none;">
							        	<h5 class="desc-post-title">{{otherPost.title}}</h5>
							    	</a>

							        <p style="font-size:12px;">By: <a href="<?php echo base_url(''); ?>{{otherPost.author}}" style="text-decoration:none; color:#c50000;">{{otherPost.author}}</a> | {{otherPost.date}}</p>
									<p class="desc-post-description" style="font-size:12px;">{{otherPost.description}}</p>
								</div>
							</div>
						</div>
					</div>
					<div class="col-lg-12" ng-show="empty"><h3>No posts to display</h3></div>
				</div>
				<div class="clearfix"></div>
			</div>
		</div>
	</div>
</div>

<div id="bottom">
	<div class="clearfix"></div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<!-- slider div -->
				
				<div class="breaking-news" id="mojopicks-scroll-point" style="border:0px;">
					<a href="<?php echo base_url(); ?>mojoPicks" class="dashboard-categ-link">
						<div class="breaking-news-title col-lg-9" style="width:68.5%; border-right:3px solid #e8e1e2;">
							<i class="glyphicon glyphicon-flash"></i><strong>  MOJOPICKS</strong>
						</div>
					</a>
					
					<a href="<?php echo base_url(); ?>poll" class="dashboard-categ-link">
						<div class="breaking-news-title col-lg-3" style="border-left:3px solid #e8e1e2;">
							<i class="glyphicon glyphicon-stats"></i><strong>  POLL</strong>
						</div>
					</a>
				</div>

				<div class="row">
					<div class="col-lg-9" id="bottom-news-slide" ng-controller="FeaturedCtrl" style="width:67.3%">
						<div class="bottom-slides" id="bottom-slides">
							<div class="bottom-slide">								
								<div class="bottom-slider-post col-lg-4" ng-repeat="post in posts | limitTo:5">
									<div class="bottom-post-image">
										<a href="<?php echo base_url(''); ?>single/{{post.slug}}"><img src="{{post.files[0].thumb}}" alt=""></a>
									</div>
									<div class="videoCamera" ng-show="post.showVideo">
										<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
									</div>
									<div class="bottom-content" >
										<h5><a href="<?php echo base_url(); ?>single/{{post.slug}}" class="title-link">{{post.title}}</a></h5>
										<p style="font-size:12px;">By: <span><a href="<?php echo base_url(); ?>{{post.author}}" class="author-name-link">{{post.author}} </a></span>| {{post.date}}</p>
										<p style="font-size:12px;"><span ng-repeat="hashtag in post.hashtags track by $index">#{{hashtag}} </span>&nbsp;&nbsp;</p>
									</div>
								</div>
								<div class="clearfix"></div>
							</div>
						</div>
					</div>		
					<div class="col-lg-4" style="padding-left:10px;padding-right:0px;width: 330px;";>
						<div id="poll" class="poll-container" ng-controller="pollCtrl" style="min-height : 265px;" ng-cloak>							
							<div class="poll-content text-overflow">
								<div class="poll-question">
									{{poll.question}}
								</div>
								<div class="poll-options" ng-hide="isAnswered">
									<div class="option" ng-repeat="option in poll.answers">
										<input type="radio" name="poll-answer" id="opt{{$index+1}}" value="{{option.id}}" ng-model="$parent.selectedOption">
										<label for="opt{{$index+1}}"> {{option.answer}} </label>
									</div>
								</div>
								<div class="poll-answers" ng-show="isAnswered">
									<div class="option" ng-repeat="result in results">
										<span class="option-text">{{result.option}} ({{result.count}})</span>
										<div class="option-bar">										
											<div class="perc" style="width: {{result.percentage}}%"></div>
										</div>
									</div>								
								</div>
								<div class="poll-submit" ng-click="submitPoll()" ng-hide="isAnswered">
									Submit
								</div>
							</div>
						</div>
					</div>
				
				</div>
				<!-- slider div end -->
			</div>
		</div>
	</div>
</div>
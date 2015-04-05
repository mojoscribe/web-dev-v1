<div class="container" id="dashboard-page" ng-controller="UserHomeCtrl" ng-cloak>

	<div class="row">
		<div class="col-lg-12">
		<div class="row">
			<div class="col-lg-12">
				<div class="clearfix"></div>		
				<!-- For slider -->
				<a href="<?php echo base_url('trending'); ?>">
				<div class="breaking-news" style="margin-top: 5px;">
					<div class="breaking-news-title col-lg-10">
						<i class="glyphicon glyphicon-flash"></i><strong>  TRENDING NEWS</strong>
					</div>
				</div>
				</a>
				<div class="recent-news" ng-controller="TrendingNewsCtrl">
					<div class="upper-grid">
						<div class="grid-left">
							<div ng-repeat="post in trendingNewsPostsLeft" class="grid-box-{{$index+1}} fadeOutAnim fadeInAnim">

								<a href="<?php echo base_url('single/{{post.slug}}'); ?>">
									<img src="{{post.files[0].small}}">
								</a>

								<div class="videoCamera" ng-show="post.showVideo">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>

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
							<div ng-repeat="post in trendingNewsPostsRight" class="grid-box-{{$index+5}} fadeOutAnim fadeInAnim">
								
								<a href="<?php echo base_url('single/{{post.slug}}'); ?>">
									<img src="{{post.files[0].thumb}}">
								</a>

								<div class="videoCamera" ng-show="post.showVideo">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>

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
					
					<div class="side-bar-hashtags" ng-controller="HeaderCtrl">
<!--						<div class="recent-label"></div>
 						<div class="newsType label"><a href="">Trending</a></div>

						<div class="breaking-label">
						</div>
						<div class="newsType label">Breaking</div>

						<div class="mojopicks-label">
						</div>
						<div class="newsType label">MoJo Picks</div> -->
						
					</div>
				</div>
				
			</div>
		</div>
			<a href="<?php echo base_url('recent'); ?>" class="col-lg-2 pull-right" style="margin-top:5px; font-size:14px; color:#c50000;">See All Recent News <i class="glyphicon glyphicon-chevron-right"></i></a>
			
			<div class="col-lg-12" id="bottom-news-slide" ng-show="locationsData">
				<a href="<?php echo base_url(); ?>page/location" class="dashboard-categ-link">
					<div class="breaking-news" style="margin-top: 5px;">
						<div class="breaking-news-title col-lg-10">
							<i class="glyphicon glyphicon-flash"></i><strong>  News from your Area</strong>
						</div>
					</div>
				</a>
				<div class="buttons leftButton" style="position:absolute; left:-40px; top:125px;" id="left" ng-click="left($event)"></div> 
				<div class="bottom-slides" id="bottom-slides" style="width: 100%; min-height: 290px;">
					<div class="bottom-slide">								
						<div class="bottom-slider-post col-lg-3" ng-repeat="post in locationsData | limitTo:10">
							<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
								<div class="bottom-post-image">
									<img src="{{post.files[0].thumb}}" alt="">
								</div>
								<div class="videoCamera" ng-show="post.showVideo">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>
							</a>

							
							<div class="bottom-content" style="height:130px;">
								<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link"><h5><strong>{{post.headline}}</strong></h5></a>
								<p style="font-size:12px;">By : <a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">{{post.author}}</a></p>
								<p class="date-tags" style="font-size:12px;">{{post.date}}</p>
								<p class="text-over"><span>{{post.location}}</span></p>
							</div>
						</div>
					</div>
				</div>
				<div class="buttons rightButton" style="position:absolute; right:-42px; top:125px;" id="right" ng-click="right($event)"></div>
			</div>	

			<div class="col-lg-12" id="bottom-news-slide" ng-repeat="category in categoryData">
				<a href="<?php echo base_url(); ?>categories?categId={{category.id}}" class="dashboard-categ-link">
					<div class="breaking-news" style="margin-top: 5px;">
						<div class="breaking-news-title col-lg-10">
							<i class="glyphicon glyphicon-flash"></i><strong>  {{category.name}}</strong>
						</div>
					</div>
				</a>
				<div class="buttons leftButton" style="position:absolute; left:-40px; top:125px;" id="left" ng-click="left($event)"></div> 
				<div class="bottom-slides" id="bottom-slides" style="width: 100%; max-height: 320px;">
					<div class="bottom-slide">								
						<div class="bottom-slider-post col-lg-3" ng-repeat="post in category.posts track by $index | limitTo:5">
							<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
								<div class="bottom-post-image">
									<img src="{{post.files[0].thumb}}" alt="">
								</div>
								<div class="videoCamera" ng-show="post.showVideo">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>
							</a>

							
							<div class="bottom-content" >
								<a href="<?php echo base_url(''); ?>single/{{post.slug}}" class="title-link"><h5><strong>{{post.headline}}</strong></h5></a>
								<p style="font-size:12px;">By : <a href="<?php echo base_url(''); ?>{{post.author}}" class="author-name-link">{{post.author}}</a></p>
								<p class="date-tags" style="font-size:12px;">{{post.date}}</p>
							</div>
						</div>
					</div>
				</div>
				<div class="buttons rightButton" style="position:absolute; right:-42px; top:125px;" id="right" ng-mouseleave="right($event)"></div>
			</div>	
			<div class="clearfix"></div>
			

	<!-- 		<div class="col-lg-12 preferences-news">
				<div class="breaking-news" style="margin-top: 5px;">
					<div class="breaking-news-title col-lg-10">
						<i class="glyphicon glyphicon-flash"></i><strong>  Shared Posts</strong>
					</div>
				</div>					
				<div class="sharedPosts" ng-class="{noPosts:empty}">
					<div ng-show="empty">
						<p style="text-align:center; font-size:18px;">Hey! You haven't shared anything yet.</p>
						<!-- <a href="<?php echo base_url('mojoPicks'); ?>" ><p style="text-align:center;">Start Sharing Now</p></a> -->
				<!--	</div>
					<div class="other-news-column col-lg-3" ng-hide="empty" ng-repeat="sharedPost in sharedPosts" style="margin-right:15px; padding:0px;">
						<a href="<?php echo base_url(''); ?>single/{{sharedPost.slug}}">
							<img src="{{sharedPost.files[0].thumb}}" class="" style="height:200px; width:262px;">
						</a>

						<div class="videoImage" ng-show="post.showVideo">
							<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
						</div>
						<div class="other-news-content" >
							<a href="<?php echo base_url(''); ?>single/{{sharedPost.slug}}"><h5><strong>{{sharedPost.title}}</strong></h5></a>
							<h6>By: <a href="<?php echo base_url(''); ?>{{sharedPost.author}}">{{sharedPost.author}}</a> | {{sharedPost.date}}</h6>
							<h6><span ng-repeat="hashtag in sharedPost.hashtags track by $index">
									<a href="<?php echo base_url(''); ?>search?q={{hashtag}}">#{{hashtag}},</a>
								</span></h6>
						</div>
					</div>
				</div>
			</div> -->
		</div>
	</div>
</div>
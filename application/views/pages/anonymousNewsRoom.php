<div id="news-room">
	<div class="cover">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 cover-content" style="padding:0px;">
					<div class="col-lg-3 news-room-profile-picture">
						<img class="img-thumbnail" src="<?php echo base_url('assets/images/mojo-m.jpg'); ?>">
					</div>
					<div class="col-lg-8 news-room-cover-video" id="newsRoomSlider">
						<div class="cover-video">
							<?php if(null != $posts){ foreach ($posts as $post) { ?>
							<div class="item">
								<img src="<?php echo $post['files'][0]['file']; ?>" style="margin-left:auto; margin-right:auto; display:block; height:245px; width:770px;" alt="">
							</div>

							<?php }
							} ?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12" style="min-height:700px;height:auto;">
				<div class="profile-title">
					<div class="col-lg-4 col-lg-offset-4" style="text-align:center; padding:5px;">
						<h4><strong>Anonymous News Desk</strong></h4>
						<!-- <input type="hidden" name="userId" id="userId" value="<?php echo $data['userId']; ?>"> -->
					</div>
				</div>
				<div class="profile-details" style="height:200px;">					
					<div class="user-details">
						<h3><strong>Anonymous</strong></h3>

						<div class="user-statistics col-lg-5">
							<p class="col-lg-6"><strong>Total Posts</strong></p>
							<p class="col-lg-6">4/5</p>
							<p class="col-lg-6"><strong>Subscribers</strong></p>
							<!-- <p class="col-lg-6"><?php echo $data['subscribers']; ?></p> -->
						</div>
						<div class="clearfix"></div>
					</div>
				</div>

				<div class="news-room-video-subscriptions">
					<div>
						<div class="subscriptions-button-panel col-lg-12">
							<div class="news-room-myVideos news btn btn-default col-lg-3" ng-click="getRecentPosts()">
								Recent Posts								
							</div>
						</div>
					</div>
					<div class="clearfix"></div>
					
					<div class="video-content text-overflow" ng-hide="contentHide">
						<?php if(null == $posts){ ?>
						<div style="text-align:center" class="error-message" ng-show="empty">
							<h3>There are no Posts to display for now</h3>
						</div>
						
						<?php
					}else{
						 foreach ($posts as $post) { ?>

						<div class="video-post col-lg-3">
							<a href="<?php echo base_url(''); ?>single/<?php echo $post['slug']; ?>">
								<img src="<?php echo $post['files'][0]['file']; ?>">
							</a>
							<div class="video-post-details">
								<div class="video-post-title">
									<a href="<?php echo base_url(''); ?>single/<?php echo $post['slug']; ?>"><h4 class="text-overflow"><?php echo $post['headline']; ?></h4></a>
								</div>

								<div class="video-post-description text-overflow">
									<?php foreach ($post['hashtags'] as $hashtag) { ?>
									<span><a href="<?php echo base_url(''); ?>search?q=<?php echo $hashtag['hashtag']; ?>">#<?php echo $hashtag['hashtag']; ?></a>&nbsp; </span>
									&nbsp;
									<?php } ?>
								</div>

								<div class="video-post-date pull-right">
									<strong><?php echo $post['date']; ?></strong>
								</div>						
							</div>
						</div>

						<?php } 
						}?>

					</div>
				</div>
			</div>
		</div>
	</div>
</div>

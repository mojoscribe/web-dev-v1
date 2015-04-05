<div id="news-room" ng-controller="newsRoomCtrl">
	<div class="cover">
		<div class="container">
			<div class="row">
				<div class="col-lg-12 cover-content" style="padding:0px;">
					<div class="col-lg-3 news-room-profile-picture">
						<?php if(empty($data['profilePicture'])){ ?>
						<img src="<?php echo base_url('assets/images/mojo-dummy-user-newsRoom.png'); ?>" alt="">
						<?php }else{ ?>
						<img class="img-thumbnail" src="<?php echo $data['profilePicture']; ?>">
						<?php } ?>
					</div>
					<div class="col-lg-8 news-room-cover-video" id="newsRoomSlider">
						<div class="cover-video">
							<div class="message" ng-show="empty" style="text-align:center; margin-top:95px;">
							<p style="font-size:22px;">No posts available</p>
							</div>
							<div ng-repeat="post in files" class="item" ng-if="current[$index]" ng-hide="empty">
								<img src="{{post.file}}" alt="">
								<div class="videoCamera" ng-show="post.showVideo" style="top:16px; left:18px;">
									<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row">
			<div class="col-lg-12">
				<div class="profile-details">
					<div class="profile-title" style="">
						<div class="" style="text-align:center; padding:5px; position:relative;">
							<h4><strong><?php echo ucfirst($data['userName']."'s");?> News Desk</strong></h4>
							<input type="hidden" name="userId" id="userId" value="<?php echo $data['userId']; ?>">
						
							<?php if(isset($_SESSION['userName']) && $_SESSION['userName'] != $data['userName']){ ?>
							<div class="pull-right col-lg-2" style="float:none;position:absolute;right:0px;top:0px;">
								<a href="" style="padding:5px;" ng-click="followUser()" class="btn btn-default rounded button follow pull-right">
								<strong>
									<span ng-show="followed">Following</span>
									<span ng-hide="followed">Follow</span>
								</strong>
								</a>
							</div>
							<?php } ?>
						</div>
					</div>
					<div class="user-details">
						<h3><strong><?php echo $data['userName']; ?></strong></h3>
						<div class="user-description">
							<?php echo $data['about']; ?>
							<br><strong><?php echo $data['location']; ?></strong>
						</div>
						<div class="user-statistics col-lg-5">
							<!-- <p class="col-lg-6"><strong>Reporter Level</strong></p>
							<p class="col-lg-6">4/5</p> -->
							<p class="col-lg-6"><strong>Subscribers</strong></p>
							<p class="col-lg-6"><?php echo $data['subscribers']; ?></p>
							<p class="col-lg-6"><strong>Member Since</strong></p>
							<p class="col-lg-6"><?php echo $data['joinDate']->format("d-M-Y"); ?></p>
						</div>
						<div class="clearfix"></div>
						<!-- <div class="edit-profile-link pull-right">
							<a href="<?php echo base_url('profile'); ?>" class="btn btn-default rounded button"><i class="glyphicon glyphicon-pencil"></i><strong>Edit Profile</strong></a>
						</div> -->
					</div>
					<div class="profile-title-bottom"></div>
				</div>

				<div class="clearfix"></div>

				<div class="news-room-video-subscriptions" style="margin-top:40px;">
					<div>
						<div class="subscriptions-button-panel col-lg-12">
							<div class="news-room-myVideos btn btn-default col-lg-3" ng-click="getRecentPosts()">
								<?php echo ucfirst($data['userName']); ?>'s Posts
								<i class="glyphicon glyphicon-chevron-down pull-right" ng-show="myPosts" style="width: auto;"></i>
								<i class="glyphicon glyphicon-chevron-right pull-right" ng-hide="myPosts" style="width: auto;"></i>
							</div>

							<div class=" btn btn-default news-room-ratedVideos col-lg-3" ng-click="getRatedPosts()">
								<?php echo ucfirst($data['userName']); ?>'s Rated Posts
								<i class="glyphicon glyphicon-chevron-right pull-right" ng-hide="ratedPosts" style="width: auto;"></i>
								<i class="glyphicon glyphicon-chevron-down pull-right" ng-show="ratedPosts" style="width: auto;"></i>
							</div>

							<div class="news-room-subscriptions btn btn-default col-lg-3" ng-click="getSubscriptions()">
								<?php echo ucfirst($data['userName']); ?>'s Subscriptions
								<i class="glyphicon glyphicon-chevron-down pull-right" ng-show="subscriptions" style="width: auto;"></i>
								<i class="glyphicon glyphicon-chevron-right pull-right" ng-hide="subscriptions" style="width: auto;"></i>
							</div>
							<div class="clearfix"></div>
						</div>
					</div>
					<div class="clearfix"></div>
					<div class="video-content" id="newsroom-bottom" ng-hide="contentHide">
						<div style="text-align:center" class="error-message" ng-show="empty">
							<h3>There are no Posts to display for this user</h3>
						</div>
						<div class="video-post col-lg-3" ng-repeat="post in posts | limitTo:3">
							<a href="<?php echo base_url(''); ?>single/{{post.slug}}">
								<img src="{{post.file.thumb}}">
								<div class="breakVideoCamera" ng-show="post.showVideo">
								</div>
							</a>
							<div class="video-post-details">
								<div class="video-post-title">
									<a href="<?php echo base_url(''); ?>single/{{post.slug}}"><h4 class="text-overflow">{{post.title}}</h4></a>
								</div>

								<div class="video-post-description text-over">
									<span ng-repeat="hashtag in post.hashtags track by $index"><a href="<?php echo base_url(''); ?>search?q={{hashtag}}">#{{hashtag}}</a>&nbsp; </span>
									&nbsp;
								</div>

								<div class="video-post-date pull-right">
									<strong>{{post.date}}</strong>
								</div>						
							</div>
						</div>
					</div>
					<div class="video-content" ng-show="contentHide">
						<div style="text-align:center" class="error-message" ng-show="empty">
							<h3>There are no Subscriptions to display for this user</h3>
						</div>
						<div class="clearfix"></div>
						<div class="video-post col-lg-3" ng-repeat="subscription in subscriptions | limitTo:3">
							<a href="<?php echo base_url(''); ?>single/{{subscription.followedUserRecentPost.slug}}">
								<img class="img-thumbnail" src="{{subscription.followedUserPicture}}">
							</a>
							<div class="video-post-details">

								<div class="video-post-description">
									<p><span><a href="<?php echo base_url(''); ?>{{subscription.followedUserUserName}}">{{subscription.followedUserUserName}}</a></span></p>
								</div>

								<div>
									<ul class="newsRoom-subscription-meta">
										<li>
											<strong>Subscribers</strong>: {{subscription.subscribers}}
										</li>

										<li>
											<strong>Reporter Level</strong>: {{subscription.reporterLevel}}
										</li>
									</ul>
								</div>

								<div class="video-post-date pull-right">
									Joined on: <strong>{{subscription.followedUserJoinDate}}</strong>
								</div>						
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

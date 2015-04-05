
<div class="container" ng-controller="SinglePage">
	<div class="row">		
		<div class="col-lg-12">
			<div class="clearfix"></div>

			<div id="single">
				<div class="post">
					<div class="media">
						<?php if($postData['postType'] == "Image") { ?>
							<?php if(count($postData['file'] )< 2) { ?>
								<img src="<?php echo $postData['file'][0]['bigImage']; ?>" alt="" style="margin-right:auto; height:400px; width:770px; margin-left:auto; display:block;">
							<?php } else { ?>
								<ul class="bxslider">
									<?php foreach($postData['file'] as $img) { ?>
										<li>
											<img src="<?php echo $img['bigImage']; ?>" alt="" style="margin-right:auto; height:400px; width:770px; margin-left:auto; display:block;">
										</li>
									<?php } ?>
								</ul>
								
							<?php } ?>
						<?php } else { ?>
							<video id="videoId" class="video-js vjs-default-skin vjs-big-play-centered"
								controls preload="auto" width="100%" height="400"
								poster="<?php print_r ($postData['file'][0]['bigImage']); ?>"
								data-setup='{"example_option":true}'>
								<source src="<?php print_r ($postData['file'][0]['webm']); ?>" type="video/webm">
								<source src="<?php print_r ($postData['file'][0]['ogg']); ?>" type="video/ogg">
								<source src="<?php print_r ($postData['file'][0]['mp4']); ?>" type="video/mp4">
							</video>
						<?php } ?>

						<?php if(isset($_SESSION['id']) && $postData['postStatus'] != "DRAFT"){ ?>
						<div class="rating">
							<div class="rating-bar">
								<div class="five" ng-click="ratingSave()">
									<!-- <div class="five-two" ng-mouseenter="fiveTwoHover()" ng-mouseout="hoverOut()" ng-model="rating" style="width:9px; height:36px; margin-left:auto; margin-right:auto;"></div> -->
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/level5.png'); ?>" ng-mouseout="hoverOut()" ng-show="fiveTwo" ng-mouseenter="fiveTwoHover()" style="top:-12px; z-index:9; right:-43px;  cursor:hand;">
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/empty-level5.png'); ?>" ng-mouseout="hoverOut()" ng-hide="fiveTwo" ng-mouseenter="fiveTwoHover()" style="top:-12px; z-index:9; right:-43px; cursor:hand;">
								</div>
								<div class="four" ng-click="ratingSave()">
									<!-- <div class="four-two" ng-mouseenter="fourTwoHover()" ng-mouseout="hoverOut()" ng-model="rating" style="width:9px; height:86px; margin-left:auto; margin-right:auto;"></div> -->
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/level4.png'); ?>" ng-mouseout="hoverOut()" ng-mouseenter="fourTwoHover()" ng-show="fourTwo" style="top:18px; z-index:9; right:-43px; cursor:hand;">
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/empty-level4.png'); ?>" ng-mouseout="hoverOut()" ng-mouseenter="fourTwoHover()" ng-hide="fourTwo" style="top:18px; z-index:9; right:-43px; cursor:hand;">
								</div>
								<div class="three" ng-click="ratingSave()">
									<!-- <div class="three-two" ng-mouseenter="threeTwoHover()" ng-mouseout="hoverOut()" ng-model="rating" style="width:9px; height:80px; margin-left:auto; margin-right:auto;"></div> -->
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/level3.png'); ?>" ng-mouseout="hoverOut()" ng-show="threeTwo" ng-mouseenter="threeTwoHover()" style="top:91px; z-index:9; right:-43px; cursor:hand;">
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/empty-level3.png'); ?>" ng-mouseout="hoverOut()" ng-hide="threeTwo" ng-mouseenter="threeTwoHover()" style="top:91px; z-index:9; right:-43px; cursor:hand;">
								</div>
								<div class="two" ng-click="ratingSave()">
									<!-- <div class="two-two" ng-mouseenter="twoTwoHover()" ng-mouseout="hoverOut()" ng-model="rating" style="width:9px; margin-left:auto; margin-right:auto;"></div> -->
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/level2.png'); ?>" ng-mouseout="hoverOut()" ng-show="twoTwo" ng-mouseenter="twoTwoHover()" style="top:164px; z-index:9; right:-43px; cursor:hand;">
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/empty-level2.png'); ?>" ng-mouseout="hoverOut()" ng-hide="twoTwo" ng-mouseenter="twoTwoHover()" style="top:164px; z-index:9; right:-43px; cursor:hand;">
								</div>
								<div class="one" ng-click="ratingSave()">
									<!-- <div class="one-two" ng-mouseenter="oneTwoHover()" ng-mouseout="hoverOut()" ng-model="rating" style="width:9px; margin-left:auto; margin-right:auto;"></div> -->
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/level1.png'); ?>" ng-show="oneTwo" ng-mouseout="hoverOut()" ng-mouseenter="oneTwoHover()" style="top:237px; z-index:9; right:-43px; cursor:hand;">
									<img class="thermo-chopups" src="<?php echo base_url('assets/images/empty-level1.png'); ?>" ng-hide="oneTwo" ng-mouseout="hoverOut()" ng-mouseenter="oneTwoHover()" style="top:237px; z-index:9; right:-43px; cursor:hand;">

								</div>
								<!-- <img src="<?php echo base_url('assets/images/thermo-empty.png'); ?>" style="position:absolute; height:399px; top:-7px; width:80px;"> -->
							</div>
							<div class="rating-bottom"><img src="<?php echo base_url('assets/images/level0.png'); ?>"  style="right: -10px; top:283px; position:absolute;"></div>
						</div>
						<?php } ?>
					</div>

					<?php if(isset($_SESSION['adminUserId'])){ ?>
						<button class="btn btn-primary" ng-click="makeFeatured()" ng-hide="featured" style="position:absolute; top:350px; margin-bottom:20px; margin-left:15px;">Feature in MojoPicks</button>
						<div class="" ng-show="featured" style="position:absolute; width:auto; padding:5px; height:30px; background-color:#3276b1; top:350px; margin-bottom:20px; margin-left:15px;">Featured</div>
					<?php } ?>

					<div class="post-details clearfix" style="min-height: 201px; padding: 10px 20px;">
						<div style="float:left; padding:0px;" class="col-lg-8">
							<input type="hidden" name="postId" id="postId" value=<?php echo $postData['id']; ?>>
							<div class="post-title">
								<?php echo $postData['title']; ?>
								
							</div>
							<div class="post-author">
								<a href="<?php echo base_url('').($postData['author']); ?>" style="color:#c50000;"><?php echo $postData['author']; ?></a>
							</div>
							<div class="post-desc">
								<?php echo $postData['description']; ?>
								
							</div>
							
							<div class="post-tags">
								<ul id="tags">
									<?php foreach ($postData['hashtags'] as $hashtag) { ?>
									<li class="tag">
										<a href="<?php echo base_url(''); ?>search?q=<?php echo $hashtag; ?>">#<?php echo $hashtag; ?></a>
									</li>
									<?php } ?>
								</ul>
								<div class="clearfix"></div>
							</div>

							<?php if($postData['postStatus'] != "DRAFT"){ ?>
							<div class="post-meta">
								<div class="meta-block" style="margin-left: 0px;">
									<i class="glyphicon glyphicon-eye-open"></i>
									<span> <?php echo $postData['views']; ?> Views </span>
								</div>
								<div class="meta-block">
									<i class="glyphicon glyphicon-comment"></i>
									<span> <?php echo $postData['numberOfShares']; ?> Shares </span>
								</div>
								<div class="meta-block">
									<i class="glyphicon glyphicon-bell"></i>
									<input type="hidden" value="<?php echo $postData['impact']; ?>" id="impact">
									<span> <?php echo $postData['impact']; ?> Impact </span>
								</div>
								<?php if(isset($_SESSION['id']) && ($_SESSION['userName'] != $postData['author'])){ ?>
								<div class="flag-content meta-block" ng-click="flagInit()" style="cursor:pointer;"> 
									<i class="glyphicon glyphicon-flag"></i>
									<span> Flag Content</span>
								</div>
								<?php }elseif (isset($_SESSION['id']) && ($_SESSION['userName'] == $postData['author'])) { ?>
								<div class="flag-content meta-block" style=""> 
									<i class="glyphicon glyphicon-flag"></i>
									<span><?php echo $postData['flags']; ?> people have flagged this post</span>
								</div>
								<?php } ?>
							</div>
							<?php } ?>

							<div class="meta"><strong><?php echo $postData['location']; ?></strong></div>
							
							<div class="sharePanel">
								<div class="shareButtons meta-block" data-toggle="tooltip" data-placement="bottom" title="Share on Facebook">
									<img src="<?php echo base_url('assets/images/facebook.png'); ?>" ng-click="shareFacebook()" class="socialShare">
								</div>

								<div class="shareButtons meta-block" data-toggle="tooltip" data-placement="bottom" title="Share on Google+">
									<img src="<?php echo base_url('assets/images/gplus.png'); ?>" ng-click="shareGPlus()" class="socialShare">
								</div>

								<div class="shareButtons meta-block" data-toggle="tooltip" data-placement="bottom" title="Share on Twitter">
									<img src="<?php echo base_url('assets/images/twitter.png'); ?>" ng-click="shareTwitter()" class="socialShare">
								</div>
							</div>
						</div>

						<div class="col-lg-3" style="float:right;">
							<p class="rating-meta rateNumber" ng-click="rateClick()" data-toggle="tooltip" title="Rate it now" style="cursor:pointer;">Rating: <img src="<?php echo base_url('assets/images/rating-icon.png'); ?>" style="height:20px;"> <b><?php echo $postData['rating']; ?></b></p>
							<p class="rating-meta">Posted On: <b><?php echo $postData['date']; ?></b></p>
							<p class="rating-meta">Source: <strong><?php echo $postData['source']; ?></strong></p>
						</div>
	
					</div>

				</div>	
			</div>
		</div>
	</div>
	<div class="modal fade" id="impactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
		    	<form action="">
			    	<div class="modal-header" style="padding:0px;">
				        <div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
		    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" style="margin-top:-10px;">
		    			</div>
				    	<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
				    </div>
			    	<div class="modal-body">
						<p>Thanks for rating the post</p>
						Please select impact of this post
						<select name="" id="" ng-model="userImpact.impact" class="form-control">
							<option ng-repeat="impact in impacts" value="{{impact.area}}">It has a {{impact.area}} Impact</option>
						</select>
						<br>
					    <div class="help-note" ng-click="helpNoteContentToggle()">
					    	<a href="">Why do I need to do this?</a>
					    </div>

					    <div class="help-note-content">
					    	You are being asked to do this for one of 2 reasons:
					    	<br>
1. To corroborate the uploader's assessment of the impact of this news, Or,<br>
2. To update the impact of the news, once it changes. A minor incident in a small community at the time of the upload may evolve into a national story within days.
<br>
<br>
We need your support to keep all the information on this site accurate and up-to-date.
					    </div>
				        <input type="button" class="btn btn-default" style="float:right;" value="Done!" ng-click="saveImpact()">
			      		<input type="button" class="btn btn-default" style="float:right;" value="Skip" ng-click="saveImpact()">
			      		<br>
			      	</div>
			    </form>


		    </div>
		</div>
	</div>

	<div class="modal fade" id="errorModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
		    	<div class="modal-header" style="padding:0px;">
			        <div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
	    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" style="margin-top:-10px;">
	    				<img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
	    			</div>
			    </div>
		    	<div class="modal-body" style="text-align:center;">
					<p>{{errorMessage}}</p>
		      	</div>
		    </div>
		</div>
	</div>

	<div class="modal fade" id="flagModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
		<div class="modal-dialog">
		    <div class="modal-content">
		    	<form action="" ng-submit="flagContent()">
		    	<div class="head" style="width:100%; height:50px; background-color:#c50000; padding:10px;">
			    	<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="" style="margin-top:-10px;">
			        <img src="<?php echo base_url('assets/images/deletebutton.png'); ?>" data-dismiss="modal" style="height:20px; width:20px; cursor:pointer; float:right;" alt="">
			    </div>
		    	<div class="modal-body">
					<h4>Flag Content
			    	</h4>
					<div class="form" ng-hide="flagged">
						<p>Please provide a reason for flagging this post : </p>
						<textarea name="flagReason" id="flagReason" class="form-control" ng-model="flag.reason" cols="30" rows="5"></textarea>
						<br>
				        <input type="submit" class="btn btn-default" style="float:right;" value="Submit">
			      		<br>
					</div>
		      		<div class="message" ng-show="flagged">
			    		{{message}}
			    	</div>
		      	</div>
			    </form>

		    </div>
		</div>
	</div>

</div>

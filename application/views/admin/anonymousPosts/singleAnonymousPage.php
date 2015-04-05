<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="clearfix"></div>
			<div id="single" style="margin-top:80px; margin-bottom:50px;">
				<div class="post">
					<div class="media">
						<?php if($postData['postType'] == "Image") { ?>
						 	<img src="<?php echo $postData['file'][0]; ?>" alt="" style="margin-right:auto; min-height:350px; margin-left:auto; display:block;">
						<?php } else { ?>
							<video id="videoId" class="video-js vjs-default-skin vjs-big-play-centered"
								controls preload="auto" width="100%" height="350"
								poster="<?php print_r ($postData['file'][0]); ?>"
								data-setup='{"example_option":true}'>
								<source src="<?php print_r ($postData['file'][0]); ?>" style="display:block; height:350px; width:900px;" type="video/mp4">
							</video>
						<?php } ?>

					</div>
					<div class="post-details">
						<div class="post-title">
							<?php echo $postData['title']; ?>
						</div>

						<div class="post-author">
							<?php echo $postData['author']; ?>
						</div>

						<div class="post-date">
							<?php echo $postData['date']; ?>
						</div>
						<div class="post-desc">
							<?php echo $postData['description']; ?>
						</div>
						<div class="post-tags">
							<ul id="tags">
								<?php foreach ($postData['hashtags'] as $hashtag) { ?>
								<li class="tag">
									#<?php echo $hashtag; ?>
								</li>
								<?php } ?>
							</ul>
							<div class="clearfix"></div>
						</div>

						<div class="control-buttons col-lg-4" style="padding:0px;">
							<a href="<?php echo base_url(''); ?>" class="btn btn-danger col-lg-6" style="margin-left:10px; width:40%; padding:4px; height:30px;">Disapprove</a>
						</div>
					</div>
				</div>	
			</div>
		</div>
	</div>
</div>

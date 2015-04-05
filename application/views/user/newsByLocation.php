<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  NEWS FROM YOUR AREA</strong>
				</div>
			</div>
			<div class="inner-div">
		<?php if(is_array($posts) && !is_null($posts)){ 

			foreach ($posts as $post) {?>
				<div class="col-lg-4 featured-post">
					<div class="all-posts-image" style="height:175px;">
						<a href="<?php echo base_url(''); ?>single/<?php echo $post['slug']; ?>">
							<img class="all-posts-news-image" src="<?php echo $post['files'][0]['thumb']; ?>" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">
							<?php
							if($post['postType'] == "Video"){ ?>
							<div class="videoCamera" style="top:11px; left:12px;">
								<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
							</div>
							<?php } ?>
						</a>
					</div>

					<div class="post-posts-news-content" style="background-color:#fff; min-height:141px;">
						<div class="all-posts-news-title" style="padding:8px;">
							<a href="<?php echo base_url(''); ?>single/<?php echo $post['slug']; ?>" class="text-over title-link"><?php echo $post['headline']; ?></a>
							<p style="font-size:12px;">By : <a href="<?php echo base_url('').$post['author']; ?>" class="author-name-link"><?php echo $post['author']; ?></a> | <?php echo $post['date']; ?></p>
							<div class="text-over" style="font-size:12px;"><?php echo $post['description']; ?></div>
						</div>

						<div class="all-posts-news-tags" style="background-color:#fff; padding:8px; font-size:12px;">
							<?php foreach($post['hashtags'] as $hashtag){ ?>
							<a href="<?php echo base_url(''); ?>search?q=<?php echo $hashtag; ?>" class="hashtag-link"><span>#<?php echo $hashtag; ?></span></a>&nbsp;
							<?php } ?>
						</div>
					</div>
				</div>
		<?php }?>
		<?php } ?>
			</div>
		</div>
	</div>	
</div>
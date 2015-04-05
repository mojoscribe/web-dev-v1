<div class="container">
	<div class="row">		
		<div class="col-lg-12" id="anonymous-posts">
			<div class="breaking-news" style="margin-top: 6px;">
				<div class="breaking-news-title col-lg-10">
					<i class="glyphicon glyphicon-flash"></i><strong>  TRENDING NEWS</strong>
				</div>
			</div>
			<div class="inner-div">
				<?php foreach ($posts as $searchResult) { ?>				
				<div class="col-lg-4 featured-post">
					<div class="all-posts-image" style="height:175px;">
						<a href="<?php echo base_url('single/' . $searchResult['slug']); ?>">
							<img class="all-posts-news-image" src="<?php echo $searchResult['files'][0]['thumb']; ?>" style="display:block; height:174px; width:100%; margin-left:auto; margin-right:auto;">						
							<?php if($searchResult['postType'] == "Video"){ ?>
							<div class="videoCamera">
								<img src="<?php echo base_url('assets/images/mojo-camera.png'); ?>" alt="">
							</div>
							<?php } ?>
						</a>
					</div>

					<div class="all-posts-news-content" style="width:100%; height:auto;">
						<div class="all-posts-news-title text-overflow">
							<a href="<?php echo base_url()."single/".$searchResult['slug']; ?>" class="title-link"><?php echo $searchResult['title']; ?></a>
							<p style="font-size:12px;">By: <a href="<?php echo base_url().$searchResult['author']; ?>" class="author-name-link"><?php echo $searchResult['author']; ?></a> | <?php echo $searchResult['date']; ?></p>
							<div class="text-over" style="font-size:12px;"><?php echo $searchResult['description']; ?></div>
						</div>
						<br>
						<div class="all-posts-news-tags" style="margin-bottom:20px; font-size:12px;">
							<?php foreach($searchResult['hashtags'] as $hashtag){ ?>
								<span><a href="<?php echo base_url(''); ?>search?q=<?php echo $hashtag; ?>" class="hashtag-link">#<?php echo $hashtag; ?></a></span>&nbsp;
							<?php } ?>
							<?php if(empty($searchResult['hashtags'])) { ?>
								<span><a>&nbsp;</a></span>
							<?php } ?>
						</div>
					</div>
				</div>
				<?php } ?>
			</div>
		</div>
	</div>
</div>

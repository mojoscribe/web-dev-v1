<div class="container" id="notificationsPage">
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-9">
				<div class="header">
					<span><b>Your Notifications</b></span>
					<a href="<?php echo base_url('preferences'); ?>"><span class="pull-right">Settings</span></a>
				</div>

				<?php foreach ($notifications as $notification) {?>
				<div class="notification">
					<img class="pull-left" style="width:40px; height:40px;" src="<?php echo $notification['image']; ?>" alt="">
					<p style="padding:0px 45px;"><a href="<?php echo $notification['link']; ?>"><?php echo $notification['text']; ?></a></p>
					
					<span style="float:right;"><?php echo $notification['date']; ?></span>
				</div>
			<?php	} ?>
			</div>
		</div>
	</div>	
</div>
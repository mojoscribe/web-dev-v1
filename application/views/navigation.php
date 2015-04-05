<div class="container" id="navParent" ng-controller="HeaderCtrl">
	<?php if(isset($_GET['sessionexpired'])){ ?>
		<div class="alert alert-success alert-dismissable">
			<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
			<span>You have been logged out of the system. Please login again to continue.</span>
		</div>
	<?php } ?>
	<div class="row" style="position:relative;">
		<div class="navigation col-lg-12">
			<a href="<?php echo base_url(''); ?>" id="home" class="btn btn-default col-lg-1" style="width:50px;border-top-left-radius: 15px;border-bottom-left-radius: 15px;">
				<i class="glyphicon glyphicon-home" style="font-size: 15px;color:#c50000"></i></a>
			<a href="<?php echo base_url('mojoPicks'); ?>" id="featured" class="btn btn-default col-lg-1">Mojo Picks</a>
			<a href="<?php echo base_url('recent'); ?>" id="recent" class="btn btn-default col-lg-1">Recent</a>
			<a href="<?php echo base_url('page/anonymous'); ?>" id="anonymous" class="btn btn-default col-lg-1">Anonymous</a>

			<a href="<?php echo base_url('page/categories'); ?>" id="categories" class="btn btn-default col-lg-1">Categories</a>
			<div class="categories-menu" style="display:none; width:200px; position:absolute; z-index:999; top:45px; left:401px;border-radiud:0px;padding:0px;background:transparent;">

				<div class="list-group" style="margin-bottom: 0px;padding: 0px;border: 0px;height: 0px;">
					<div class="list-group-item" ng-repeat="category in categories" style="padding: 8px;height: 40px;border-left: 0px;border-right: 0px;border-top:0px;border-bottom:1px solid #ccc; z-index:999">
						<a href="<?php echo base_url(''); ?>categories?categId={{category.id}}" style="text-decoration:none; color:#333;">{{category.name}}</a>
					</div>
				</div>
			</div>
			<a href="<?php echo base_url('poll'); ?>" id="polls" class="btn btn-default col-lg-1">Polls</a>
			<a href="http://mojoscribe.wordpress.com/" target="_blank" id="aboutUs" class="btn btn-default col-lg-1" style="border-top-right-radius: 15px;border-bottom-right-radius: 15px;border-right:1px solid #ccc;">Blog</a>
			
			<div class="form-group col-lg-2 landing-page-search">
				<form action="<?php echo base_url('search'); ?>" method="GET">
					<input class="form-control searchBox text-over" style="padding-right:35px;" name="q" id="searchQuery" type="text" placeholder="Search this Website"
					<?php if(isset($_GET['q'])){ ?>value="<?php echo $searchQuery; ?>"<?php } ?>></input>
					<input type="submit" class="searchImage" value=""/>
				</form>
			</div>
		</div>
		
	</div>
</div>

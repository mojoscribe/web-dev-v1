<div class="container">
	<div class="row">
		<div class="col-lg-3">
			<div class="list-group" style="margin-top: 0px;">
				<a class="list-group-item" id="newsRoom" href="<?php echo base_url('newsRoom'); ?>"><i class="glyphicon glyphicon-user"></i> News Room</a>
				<a class="list-group-item" id="posts-menu" href=""><i class="glyphicon glyphicon-align-justify"></i> Posts</a>
				<div class="posts-option" style="display:none;">
					<a href="<?php echo base_url('allPosts'); ?>" class="list-group-item" id="allPosts"> <i class="glyphicon glyphicon-th-list"></i> All Posts</a>
					<a href="<?php echo base_url('post'); ?>" id="addNew" class="list-group-item"><i class="glyphicon glyphicon-file"></i> Add New Post</a>
				</div>
				<a href="<?php echo base_url('drafts'); ?>" id="drafts" class="list-group-item"><i class="glyphicon glyphicon-pencil"></i> Drafts</a>
				<a class="list-group-item" href="" id="settings-menu"><i class="glyphicon glyphicon-cog"></i> Settings</a>			
				<div class="settings-option" style="display:none;">
					<a href="<?php echo base_url('profile'); ?>" class="list-group-item" id="profile-tab"> <i class="glyphicon glyphicon-picture"></i> Profile Settings</a>
					<a href="<?php echo base_url('preferences'); ?>" id="preferences" class="list-group-item"><i class="glyphicon glyphicon-th"></i> Preferences Settings</a>
				</div>	
			</div>
		</div>

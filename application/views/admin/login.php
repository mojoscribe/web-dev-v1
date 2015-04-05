<div class="container">
	<div class="row">
		<div class="col-lg-4">
		</div>
		<div class="col-lg-4">
			<h2>&nbsp;</h2>			
			<h2>
				Mojo Scribe <small>Admin Login</small>
			</h2>			
			<?php if(isset($_GET['wrong'])) {?>
				<div class="alert alert-danger alert-dismissable">
	              <button type="button" class="close" data-dismiss="alert" aria-hidden="true">!</button>
	              Incorrect Username and/or Password! Please Check!
	            </div>
            <?php } ?>
			<form role="form" method="POST" action="<?php echo base_url('admin/authenticate'); ?>">
				<div class="form-group">
					<label>User Name</label> <input type="text" id="userName" name="userName" class="form-control">
					<!-- <p class="help-block">Enter your User Name or Email Id</p> -->
				</div>
				<div class="form-group">
					<label>Password</label> <input type="password" id="password" name="password" class="form-control">
					<!-- <p class="help-block">Example block-level help text here.</p> -->
				</div>
				<button type="submit" class="btn btn-default">Login</button>
			</form>
		</div>
	</div>
	<!-- /.row -->
</div>
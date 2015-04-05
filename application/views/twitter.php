<html>
<head>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
	<title>MojoScribe::Twitter SIgn-in</title>
</head>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<div class="col-lg-4 col-lg-offset-4">
				<h3><strong>Register with Twitter</strong></h3>

				<form action="<?php echo base_url('login/twitterLogin'); ?>" method="POST">
					<div class="form-group">
						<input type="email" class="form-control" name="email" placeholder="Enter Email">
					</div>

					<div class="form-group">
						<input type="password" class="form-control" name="password" placeholder="Password">
					</div>

					<div class="form-group">
						<input type="submit" id="twitterSubmit" class="btn btn-primary" name="twitterLogin" value="Sign In">
					</div>
				</form>
			</div>
		</div>
	</div>
</div>

</html>
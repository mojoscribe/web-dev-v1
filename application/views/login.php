<div>
	<div>
		<h2>LOGIN</h2>
		<form action="<?php echo base_url('login/authenticate'); ?>" method="POST">
			<div>
				<label>Email:</label>
				<input type="text" name="email">
			</div>

			<div>
				<label>Password:</label>
				<input type="password" name="password">
			</div>

			<div>
				<input type="submit" name="submitRegister">
			</div>
		</form>
	</div>

	<div>
		<a href="<?php echo base_url('post'); ?>">Upload</a>
	</div>

	<div>
		<h3>Forgot Password</h3>
		<form action="<?php echo base_url('register/forgotPass'); ?>" method="POST">
			<div>
				<label>Email:</label>
				<input type="text" name="email">
			</div>

			<div>
				<input type="submit" name="submitforgotPass">
			</div>
		</form>
	</div>

	<div>
		<h3>Change Password</h3>
		<form action="<?php echo base_url('register/changePass'); ?>" method="POST">
			<div>
				<label>Email:</label>
				<input type="text" name="email">
			</div>

			<div>
				<label>Password:</label>
				<input type="password" name="newpassword">
			</div>

			<div>
				<label>Re-type Password:</label>
				<input type="password" name="password">
			</div>

			<div>
				<input type="submit" name="submitforgotPass">
			</div>
		</form>
	</div>

	<div>

		<h2>Register</h2>
		<form action="<?php echo base_url('register') ?>" method="POST">
			<div>
				<label>First Name:</label>
				<input type="text" name="firstName">
			</div>

			<div>
				<label>Last Name:</label>
				<input type="text" name="lastName">
			</div>

			<div>
				<label>Email:</label>
				<input type="text" name="email">
			</div>

			<div>
				<label>Password:</label>
				<input type="password" name="password">
			</div>

			<div>
				<label>Contact Number:</label>
				<input type="text" name="contactNumber">
			</div>

			<div>
				<input type="submit" name="submitLogin">
			</div>
		</form>
	</div>
</div>
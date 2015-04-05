<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>Add Impact</h2>
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<form action="<?php echo base_url('admin/impact/save'); ?>" class="form" method="POST">
				<div class="form-group">
					<label for="name">Impact Name</label>
					<input type="text" class="form-control" name="name">
				</div>
				<input type="submit" value="Save Impact">
			</form>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>Add Category</h2>
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<form action="<?php echo base_url('admin/category/save'); ?>" class="form" method="POST">
				<div class="form-group">
					<label for="name">Category Name</label>
					<input type="text" class="form-control" name="name">
				</div>
				<div class="form-group">
					<label for="name">Category Order</label>
					<input type="text" class="form-control" name="order">
				</div>
				<input type="submit" value="Save Category">
			</form>
		</div>
	</div>
</div>
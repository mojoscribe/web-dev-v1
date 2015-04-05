<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>Edit Category</h2>
			<hr>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<form action="<?php echo base_url('admin/category/update'); ?>" class="form" method="POST">
				<div class="form-group">
					<label for="name">Category Name</label>
					<input type="text" class="form-control" name="name" value="<?php echo $category->getName(); ?>">
				</div>
				<div class="form-group">
					<label for="name">Category Order</label>
					<input type="text" class="form-control" name="order" value="<?php echo $category->getOrder(); ?>">
				</div>
				<input type="hidden" name="id" value="<?php echo $category->getId(); ?>">
				<input type="submit" value="Save Category">
			</form>
		</div>
	</div>
</div>
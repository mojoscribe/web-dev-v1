<div class="container">
	<div class="row">
		<div class="col-lg-6">
			<h2>&nbsp;</h2>
			<h2>Categories</h2>
		</div>
		<div class="col-lg-6 text-right">			
			<h2>&nbsp;</h2>
			<a href="<?php echo base_url('admin/category/add'); ?>" class="btn btn-success"> Add Category </a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<table class="table">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Category Name</th>
						<th>Order</th>
						<th>Edit</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($categories as $categ){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $categ->getName(); ?></td>
						<td><?php echo $categ->getOrder(); ?></td>
						<td> <a href="<?php echo base_url('admin/category/edit?id='.$categ->getId()); ?>" class="btn btn-success"> Edit </a> </td>
						<td> <a href="<?php echo base_url('admin/category/delete?id='.$categ->getId()); ?>" class="btn btn-danger"> Delete </a> </td>
					</tr>	
					<?php } ?>					
				</tbody>
			</table>
		</div>
	</div>
</div>
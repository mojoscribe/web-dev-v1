<div class="container">
	<div class="row">
		<div class="col-lg-6">
			<h2>&nbsp;</h2>
			<h2>User Impacts</h2>
		</div>
		<div class="col-lg-6 text-right">			
			<h2>&nbsp;</h2>
			<a href="<?php echo base_url('admin/impact/add'); ?>" class="btn btn-success"> Add Impact </a>
		</div>
	</div>

	<div class="row">
		<div class="col-lg-12">
			<table class="table">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Impact Name</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($impacts as $impact){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><?php echo $impact->getArea(); ?></td>
						<td> <a href="<?php echo base_url('admin/impact/delete?id='.$impact->getId()); ?>" class="btn btn-danger"> Delete </a> </td>
					</tr>	
					<?php } ?>					
				</tbody>
			</table>
		</div>
	</div>
</div>
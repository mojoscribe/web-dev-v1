<div class="container">
	<div class="row" style="margin-top:50px;">
		<div class="col-lg-12">
			<h2>Mojo Picks</h2>
			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Mojo Picks</li>
            </ul>
		</div>
	</div>	

	<div class="row">
		<div class="col-lg-12">
			<table class="table" id="featured">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Post Title</th>
						<th>Post Type</th>
						<th>Post Date</th>
						<th>Shares</th>
						<th>Views</th>
						<th>Flags</th>
						<th>Rating</th>
						<th>Delete</th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($featured as $feat){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><a href="<?php echo base_url('')."single/".$feat['slug']; ?>"><?php echo $feat['title']; ?></a></td>
						<td><?php echo $feat['type']; ?></td>
						<td><?php echo $feat['date']; ?></td>
						<td><?php echo $feat['shares']; ?></td>
						<td><?php echo $feat['views']; ?></td>
						<td><?php echo $feat['flags']; ?></td>
						<td><?php echo $feat['rating']; ?></td>
						<td> <a href="<?php echo base_url('admin/featured/remove'); ?>?id=<?php echo $feat['id']; ?>" class="btn btn-danger"> Remove </a> </td>
					</tr>	
					<?php } ?>					
				</tbody>
			</table>
		</div>
	</div>
</div>

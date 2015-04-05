<div class="container">
	<div class="row" style="margin-top:50px;">
		<div class="col-lg-12">
			<h2>Flagged Posts</h2>
			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Flagged</li>
            </ul>
		</div>
	</div>	

	<div class="row">
		<div class="col-lg-12">
			<table class="table">
				<thead>
					<tr>
						<th>S.No</th>
						<th>Post Title</th>
						<th>Author</th>
						<th>Created On</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php $i = 1; ?>
					<?php foreach ($flagged as $feat){ ?>
					<tr>
						<td><?php echo $i++; ?></td>
						<td><a href="<?php echo base_url('')."single/".$feat->getPost()->getSlug(); ?>"><?php echo $feat->getPost()->getHeadline(); ?></a></td>
						<td><?php echo $feat->getPost()->getAuthor()->getUserName(); ?></td>
						<td><?php echo $feat->getPost()->getCreatedOn()->format('d-M-Y'); ?></td>
						<td> <a href="<?php echo base_url('admin/flagged/warn'); ?>?userId=<?php echo $feat->getPost()->getAuthor()->getId(); ?>&postId=<?php echo $feat->getPost()->getId(); ?>" class="btn btn-danger"> Warn Author </a> </td>
					</tr>	
					<?php } ?>					
				</tbody>
			</table>
		</div>
	</div>
</div>

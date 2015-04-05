<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>
				Users
			</h2>
			
			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Users</li>
            </ul>
			
		</div>
	</div>
	
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<table class="table">
					<thead>
						<tr>
							<th class="header">Sr.No.<i class="icon-sort"></i></th>
							<th class="header">Name <i class="icon-sort"></i></th>
							<th class="header">UserName</th>
							<th class="header"> Email <i class="icon-sort"></i></th>
							<th class="header"> Action  <i class="icon-sort"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($users as $value) { ?>
							<tr class="<?php
							if ($value -> getIsBannedStatus() == 1) { echo "activeRow";
							}
 ?>">
								<td><?php echo $i; ?></td>
								<td><?php echo $value -> getFirstName() . " " .$value -> getLastName(); ?></td>
								<td><a href="<?php echo base_url().$value->getUserName(); ?>" target="_blank"><?php echo $value->getUserName(); ?></a></td>
								<td><?php echo $value -> getEmail(); ?></td>
								<td>
									<div class="btn-group">
					                  <button type="button" class="btn btn-primary">Action</button>
					                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					                  <ul class="dropdown-menu">
					                    
					                    <li>
					                    	<a href="<?php echo base_url('admin/user/ban?id=' . $value -> getId()); ?>">
					                    		 <i class="fa fa-times"></i>
						                    	<?php if($value->getIsBannedStatus() == 1){?> <?php echo "Remove Ban"; ?>
						                        <?php } else { ?>
						                    	 Ban This User  <?php } ?> </a>
					                    </li>
					                    <li>
					                    	<a href="<?php echo base_url().'admin/user/warn?id='.$value->getId(); ?>"><i class="glyphicon glyphicon-exclamation-sign"></i> Warn this User</a>
					                    </li>
					                    
					                  </ul>
					                </div>
		                		</td>
							</tr>
						<?php $i++;
								} ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
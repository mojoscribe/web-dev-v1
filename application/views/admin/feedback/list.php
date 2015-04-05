<div class="container">
	<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>
				Feedback
			</h2>
			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Feedback</li>
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
							<th class="header">Email <i class="icon-sort"></i></th>
							<th class="header"> Content <i class="icon-sort"></i></th>
							<th class="header"> Action  <i class="icon-sort"></i></th>
						</tr>
					</thead>
					<tbody>
						<?php $i=1; foreach($feedbacks as $value) { ?>
							<tr>
								<td><?php echo $i; ?></td>
								<td><?php echo $value -> getUser(); ?></td>
								<td><?php echo substr($value -> getContent(),0,80); ?></td>
								<td>
									<div class="btn-group">
					                  <button type="button" class="btn btn-primary">Action</button>
					                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">
					                  	<span class="caret"></span>
					                  	</button>
					                  <ul class="dropdown-menu">
                                        <li>
					                    	<a href="<?php echo base_url('admin/feedback/view?id=' . $value -> getId()); ?>">
					                    		 <i class="fa fa-trash-o"></i> View
					                    	 </a>
					                    </li>
					                    <li>
					                    	<a href="<?php echo base_url('admin/feedback/delete?id=' . $value -> getId()); ?>">
					                    		 <i class="fa fa-trash-o"></i> Delete
					                    	 </a>
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
<div class="container">

<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>
				Poll
			</h2>
             <div class="pull-right" >
				<a href="<?php echo base_url(); ?>admin/poll/add" class="btn btn-success" style="color:white;">
					<i class="fa fa-plus"></i>
                Add New Poll </a>
			</div>
			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Poll</li>
            </ul>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-12">
			<div class="table-responsive">
				<div>
				         <div class="col-lg-1"><b>Sr.No.</b><i class="icon-sort"></i></div>
							<div class="col-lg-9"><b>Poll Title</b><i class="icon-sort"></i></div>

							<div class="col-lg-2"><b>Action</b><i class="icon-sort"></i></div>
							</div>
						<?php if(isset($polls)){ $i=1; foreach($polls as $poll) { ?>
<div>
                                <div class="col-lg-1">
                               <?php echo $i;
									$i++;
 ?>
                                	</div>

								<div class="col-lg-9"><?php echo $poll -> getPollContent(); ?></div>

								<div class="col-lg-2">
									<div class="btn-group" style="margin-top:2px;">
					                  <button type="button" class="btn btn-primary">Action</button>
					                  <button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown"><span class="caret"></span></button>
					                  <ul class="dropdown-menu">
					                    <li>
					                    <a  href="<?php echo base_url('admin/poll/edit?id=' . $poll -> getId()); ?>"><i class="fa  fa-credit-card"></i> Edit</a>
					                    </li>
					                     <li>
					                     <a   href="<?php echo base_url('admin/poll/delete?id=' . $poll -> getId()); ?>"><i class="fa fa-trash-o"></i> Delete</a>
					                     </li>
					                    <li class="divider"></li>
					                  </ul>
					                </div>
		                		</div>
		                		</div>

						<?php }} ?>
			</div>
		</div>
	</div>
</div>
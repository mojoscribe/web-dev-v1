<div class="container">

<div class="row">
		<div class="col-lg-12">
			<h2>&nbsp;</h2>
			<h2>
				Poll
			</h2>

			<ul class="breadcrumb">
                <li><a href="<?php echo base_url('admin/home'); ?>">Dashboard</a></li>
                <li class="active">Poll</li>
            </ul>

		</div>
	</div>

<div class="row">
	<div class="col-lg-2"></div>
		<div class="col-lg-8">

			<form role="form" method="post" action="<?php echo !isset($_GET['id'])?base_url('admin/poll/add'):base_url('admin/poll/edit?id='.$_GET['id']); ?>">

              	<div class="form-group">
	                <label>Question</label>
	                <textarea class="form-control " required="required" name="questionDesc"  rows="8" style="width:100%;"><?php if(isset($poll)){echo $poll->getPollContent();} ?></textarea>
              	</div>
                <?php if(isset($poll)){
                	$options = $poll->getOptionText();
					$i=1;
					 foreach($options as $value){ ?>
              	<div class="form-group">
	                <label>Option<?php echo $i;  ?></label>
	                <input class="form-control " required="required" name="Option<?php echo $i;?>"  value=" <?php if(isset($value)){echo $value->getOptionText();} ?> ">

	                <input type="hidden" name="OptionId<?php echo $i; $i++;?>" value="<?php echo $value->getId(); ?>"
              	</div>
                <?php } } else { ?>

                	<div class="form-group">
	                <label>Option1</label>
	                <input class="form-control " required="required" name="Option1"  value="">
                 	</div>

              	<div class="form-group">
	                <label>Option2</label>
	                <input class="form-control " required="required" name="Option2"  value="">
              	</div>

              	<div class="form-group">
	                <label>Option3</label>
	                <input class="form-control "  name="Option3"  value="">
              	</div>

              	<div class="form-group">
	                <label>Option4</label>
	                <input class="form-control "  name="Option4"  value="">
              	</div>
               <?php } ?>
              	<div>
                	<input type="submit" value="Submit Question" class="btn btn-primary">
              	</div>

			</form>
		</div>
</div>
</div>
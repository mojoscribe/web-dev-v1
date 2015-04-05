<div class="container">
	<div class="row">
		<div class="col-lg-12" style="height:50px;">
			</div>
		<div class="col-lg-12">
          <div class="col-lg-2"></div>
			<div class="col-lg-8" style="background:white;">

				<?php if(isset($message)) { ?>
				<div class="alert alert-dismissable alert-success" id="message">
	              <button type="button" class="close" data-dismiss="alert">×</button>
	              <?php echo $message; ?>
	            </div>
            <?php } else { ?>

				<h4>Want to share some thoughts...</h4>
				<p>We would like to hear them..</p>
				<?php } ?>
			<form method="POST" action="<?php echo base_url(); ?>feedback">
				<div class="form-group">
					<label >Email</label>
					<input class="form-control" type="email" name="email" style="border-radius:0px;" placeholder="Enter Email Address" required="required" />
				</div>
<br>
				<div class="form-group">
					<label >Feedback</label>
					<textarea class="form-control" rows="10" name="content" style="border-radius:0px;" required="required" placeholder="Enter your suugestions here"></textarea>
				</div>
				<br>
				<div class="form-group">
                  <input type="submit" class="btn btn-primary" value="Submit"  style="background-color: #C50000; font-size:14px; border-radius:0px; width:80px; border-color:#c50000;" />
				</div>
				<br>
			</form>
			</div>
			<div class="col-lg-2"></div>
		</div>
	</div>
</div>
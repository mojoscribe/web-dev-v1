<div>
	<div>
		<form action="<?php echo base_url('post/submitPost'); ?>" method="POST" enctype="multipart/form-data">
			<input type="file" name="uploadFile" id="uploadFile">

			<input type="submit" name="fileSubmit">
		</form>
	</div>

	<img id="uploadedFile-preview" width="200px"/>

	
</div>

<script type="text/javascript">var baseUrl = '<?php echo base_url(); ?>';</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/upload.js'); ?>"></script>
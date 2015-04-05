<html ng-app>
<head>
	<meta charset="UTF-8">
	<title>MojoScribe</title>
	<link rel="icon" type="image/png" href="<?php echo base_url(''); ?>assets/images/mojoLogo.png"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
	<!-- Slider css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/thumbelina.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/overide.css">
    <!-- <link rel="stylesheet" href="<?php echo base_url('assets/css/video-js.css')?>" /> -->
    <!-- <link href="//vjs.zencdn.net/4.6/video-js.css" rel="stylesheet"> -->

    <?php if(isset($links)) {

	foreach($links as $link) { ?>
	<link href="<?php echo base_url('assets/css/' . $link); ?>" rel="stylesheet"></link>
	<?php }
		}
	?>

    <script type="text/javascript">
	  document.createElement('video');document.createElement('audio');document.createElement('track');
	</script>
</head>

<body >
	<?php
		$uniqueValues = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']); //add more/less/any "unique" values, see comments
	    $_SESSION['XSRF_TOKEN'] = sha1(uniqid(microtime() . $uniqueValues, false));
	?>
	<input type="hidden" id="csrf" value="<?php echo $_SESSION['XSRF_TOKEN']; ?>">
	<header id="top-header">
		<div class="container">
			<div class="row" ng-controller="HeaderCtrl">
				<div class="col-lg-6">
					<div class="logo-image">
						<a href="<?php echo base_url('/'); ?>"><img src="<?php echo base_url('assets/images/logo.png'); ?>" alt=""></a>
					</div>
				</div>

				<div class="col-lg-6 pull-right">
					<div class="col-lg-6 pull-right login-reg-buttons">
						<div class="pull-right">
							<?php
							 if(isset($_SESSION['userName'])) {?>
								<button href="<?php echo base_url('login/logout'); ?>" class="btn btn-default top-nav" style="margin-right:15px;">Logout</button>
							<?php }?>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-lg-6 text-right">
				<a href="<?php echo base_url('logout'); ?>" class="btn btn-default" id="logout">Logout</a>
			</div> -->
		</div>
	</header>
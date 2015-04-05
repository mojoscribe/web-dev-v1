<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html ng-app>
<head>
	<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=0.8045, maximum-scale=1" />
	<title><?php if(isset($isSingle)){ echo $postData['title'] . " :: ";
			}elseif($_SERVER['REQUEST_URI'] == '/terms') {
				echo "Terms and Conditions"." :: ";
			}
	 ?>	MojoScribe  </title>
	<link rel="icon" type="image/png" href="<?php echo base_url(''); ?>assets/images/mojoLogo.png"/>
	<link rel="stylesheet" href="<?php echo base_url('assets/css/bootstrap.min.css'); ?>">
	<link rel="stylesheet" href="<?php echo base_url('assets/css/style.css'); ?>">
	<!-- Slider css -->
    <link rel="stylesheet" href="<?php echo base_url('assets/css/thumbelina.css') ?>" />
    <link rel="stylesheet" href="<?php echo base_url('assets/font-awesome/css/font-awesome.min.css');?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/jquery-ui.css">
	<link rel="stylesheet" href="<?php echo base_url('assets/select2/select2.css'); ?>">
    <link rel="stylesheet" href="<?php echo base_url();?>assets/css/overide.css">
    <link rel="stylesheet" href="<?php echo base_url('assets/css/video-js.css')?>" />

    <link type="application/rss+xml" href="<?php echo base_url('rss/feed'); ?>" rel="alternate">
    <!-- <link href="//vjs.zencdn.net/4.6/video-js.css" rel="stylesheet"> -->

    <?php if(isset($isSingle)){?>
		 <!-- Schema.org markup for Google+ -->
		<meta itemprop="name" content="<?php echo $postData['title']; ?>">
		<meta itemprop="description" content="<?php echo base_url(''); ?>">
		<meta itemprop="image" content="<?php echo $postData['file'][0]['bigImage']; ?>">

		<!-- Twitter Card data -->
		<meta name="twitter:site" content="@mojoscribe">
		<meta name="twitter:title" content="<?php echo $postData['title']; ?>">
		<meta name="twitter:description" content="<?php echo $postData['author'].":".base_url().$postData['author']."<br>".$postData['description']; ?>">

		<!-- Open Graph data -->
		<meta property="og:title" content="<?php echo $postData['title']; ?>" />
		<meta property="og:type" content="article" />
		<meta property="og:author" content="<?php echo $postData['author'] ?>">
		<meta property="fb:app_id" content="1395534207395495">
		<meta property="og:url" content="<?php echo base_url('single/'.$postData['slug']); ?>" />

		<meta property="og:image" content="<?php echo $postData['file'][0]['bigImage']; ?>" />
		<meta property="og:description" content="<?php echo $postData['description']; ?>" />
		<meta property="og:site_name" content="MojoScribe" />
    <?php } ?>

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

<body ng-cloak>
	<?php
		$uniqueValues = md5($_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']); //add more/less/any "unique" values, see comments
	    // $_COOKIE['XSRF_TOKEN'] = sha1(uniqid(microtime() . $uniqueValues, false));
	    // $value = sha1(uniqid(time() . $uniqueValues, true));
	    // setcookie("XSRF_TOKEN", $value);
	    $_SESSION['fromView'] = true;
	?>
	<header id="top-header">
		<div class="container">
			<div class="row" ng-controller="HeaderCtrl" style="margin:0px;">
				<?php if(isset($_SESSION['id'])){ ?>
				<div class="col-xs-5">
					<div class="col-xs-12">
						<div class="row user-profile-pic">
							<a href="<?php echo base_url('newsRoom'); ?>" data-toggle="tooltip" data-placement="bottom" title="News Room"><img class="img-circle" src="<?php echo $data['picture']; ?>" style="height:50px; width:50px;"/></a>
						</div>

						<div class="col-xs-9">
							<h3 style="margin-top: 20px;">
							<a class="header-author" style="color:#fff;" href="<?php echo base_url('newsRoom'); ?>" data-toggle="tooltip" data-placement="bottom" title="News Room"><?php echo $data['userName'];?></a>
							</h3>
						</div>
					</div>
				</div>
				<?php } ?>

				<div class="col-xs-3">
					<div class="logo-image">
						<a href="<?php echo base_url('/'); ?>">
							<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
						</a>
					</div>
				</div>

				<div class="col-xs-4 pull-right" style="width: 332px;float: right!important;">
					<div class="col-xs-10 pull-right login-reg-buttons">
						<div class="pull-right">
							<?php
							 if(false == isUserLoggedIn()) {?>
							<button href="" class="btn btn-default top-nav" id="register">Register</button>

							<button href="" class="btn btn-default top-nav" id="login">Login</button>
							<div class="register-menu" role="menu" style="display:none;">
								<form action="" id="registrationForm" method="POST">
									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
										<input type="email" name="email" id="regEmail" ng-model="register.email" ng-blur="checkEmail()" class="form-control register-input" data-toggle="tooltip" data-placement="left" placeholder="Email Address">
									</div>

									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" id="regUserName" name="userName" ng-model="register.userName" ng-blur="checkUserName()" data-toggle="tooltip" data-placement="left" class="form-control register-input" placeholder="Reporter Handle">
									</div>

									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
										<input type="password" name="password" ng-model="register.password" id="password" ng-blur = "checkPasswordLength()" data-toggle="tooltip" data-placement="left" class="form-control register-input" placeholder="Password">
									</div>

									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
										<input type="password" id="confirmPassword" ng-model="register.confirmPassword" ng-blur="checkPassword()" data-toggle="tooltip" data-placement="left" class="form-control register-input" placeholder="Confirm Password">
									</div>

									<input type="submit" class="register-input btn btn-default" ng-click="registerAuthenticate()" id="registerSubmit" value="Register">
								</form>
								<hr>
								<!-- <small> Or, Register Via</small><br><br> -->
								<div>
									<a href="" class="register-input btn btn-default" id="fbReg" style="padding:0px;">
										<div id="fb-register"> Register with 
											<strong>Facebook</strong>
										</div>
									</a>
								</div>

								<!-- <div>
									<a href="" class="register-input btn btn-default" id="twitterReg" style="padding:0px;"><div id="twitter-reg"> Register with <strong>Twitter</strong></div></a>
								</div> -->

<!-- 								<div>
									<a href="" class="register-input btn btn-default" id="gplusReg" style="padding:0px;"><div id="gplus-register"> Register with <strong>Google</strong></div></a>
								</div> -->
								<div id="signinButton" onclick="render()" style="padding:0px; margin-left:-5px;">
									<span href="" class="register-input icon btn btn-default" id="gplusReg" style="padding:0px;">Register with <strong>Google</strong></span>
									<!-- <span class="gplusText"></span> -->
								</div>
							</div>

							<div class="login-menu" role="menu" style="display:none;">
								<form action="" method="POST">

									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
										<input type="text" id="loginUserName" name="userName" ng-model="login.userName" class="form-control login-input" data-toggle="tooltip" data-placement="left" placeholder="Username">
									</div>

									<div class="input-group">
										<span class="input-group-addon"><i class="glyphicon glyphicon-lock"></i></span>
										<input type="password" name="password"  ng-model="login.password" class="form-control login-input" placeholder="Password">
									</div>

									<input type="submit" class="login-input btn btn-default" ng-click="loginAuthenticate()" id="login-submit" value="Login">
									<a href="<?php echo base_url(''); ?>register/forgotPassInit" style="text-align:center;"><small>Forgot Password</small></a>
								</form>
								<!-- <div style="padding:0px 80px;">
									<small> Or, Login Via</small>
								</div> -->
								<div>
									<a href="" class="btn btn-default" id="loginWithFb" style="padding:0px;"><div id="fb-register"> Login with <strong>Facebook</strong></div></a>
								</div>

								<!-- <div>
									<a href="" class="btn btn-default" id="twitterLogin" style="padding:0px;"><div id="twitter-reg"> Login with <strong>Twitter</strong></div></a>
								</div> -->

								<!-- <div>
									<a href="" class="btn btn-default" id="gplusLogin" style="padding:0px;"><div id="gplus-register"> Login with <strong>Google</strong></div></a>
								</div> -->
								<div id="loginButton" onclick="render()" style="padding:0px; margin-left:-5px;">
									<span href="" class="register-input icon btn btn-default" id="gplusReg" style="padding:0px;">Login with <strong>Google</strong></span>
									<!-- <span class="gplusText"></span> -->
								</div>
							</div>

							<?php } else{
								?>
								<a class="btn btn-default top-nav" id="notif-inactive" data-toggle="tooltip" data-placement="bottom" title="Notifications" style="background-color:transparent;border:0px solid;" ng-hide="showNotifCount" ng-click="notifications(); notifRead()">
									<div class="top-notification"></div>
									<!-- <img src="<?php echo base_url('assets/images/notif_icon.png'); ?>" style="width:15px; height:22px;"> -->
								</a>
								<a class="btn btn-default top-nav" id="notif-active" ng-click="notifications(); notifRead()" ng-show="showNotifCount">
									<img src="<?php echo base_url('assets/images/notif_icon_active.png'); ?>" style="width:15px; height:22px;">
									<div class="notif-count">{{notifLength}}</div>
								</a>
								<ul style="" id="notifications-menu" role="menu">
									<div class="notification-triangle"></div>
									<li id="notif-header" style="text-align: left;">
										<span style="font-weight:bold;">Notifications</span>
										<a href="<?php echo base_url(''); ?>notifications" ng-show="userNotifications" style="float:right;">See All Notifications</a>
									</li>
									<li class="notif-item" ng-repeat="notification in userNotifications" style="height:auto;">
										<a href="{{notification.link}}" style="height:auto; color:#333; text-decoration:none; font-size:13px; display:inline-block; text-align: left;">
											<div class="pull-left" style="width:50px;">
												<img src="{{notification.image}}" class="notification-image" style="width:100%; height:50px;" alt="">
											</div>
											<div class="pull-right" style="width:180px; padding:4px; text-align: left;">
												<span>{{notification.text}}</span>
											</div>
										</a>
									</li>

									<li class="notif-item" ng-hide="userNotifications"><span style="height:auto; color:#333; text-decoration:none; font-size:13px; display:inline-block; text-align: left; margin-top:10px; margin-bottom:10px;"><b>There are no notifications for you right now</b></span></li>
									
								</ul>
								<a class="btn btn-default top-nav top-set" id="dropdownMenu2" data-toggle="dropdown" data-toggle="tooltip" data-placement="bottom" title="Settings" style="height:35px; padding: 7px 2px 0px 2px;">
									<div class="top-settings"></div>
									<!-- <img src="<?php echo base_url('assets/images/settings-normal.png'); ?>" width="20"> -->  <!-- <span class="caret"></span> -->
								</a>
								<ul class="dropdown-menu" role="menu" aria-labelledby="dropdownMenu2" style="position:absolute; top:58px; right:-82px; border-radius:0px; font-size:15px;">
								  	<div class="notification-triangle" style="right:152px;"></div>
								  	<li role="presentation"><a href="<?php echo base_url('profile'); ?>" class="pull-left" role="menuitem"><i class="glyphicon glyphicon-picture"></i> Profile Settings</a></li>
								  	<li class="divider"></li>
								  	<li role="presentation"><a href="<?php echo base_url('preferences'); ?>" class="pull-left" role="menuitem"><i class="glyphicon glyphicon-th"></i> Preference Settings</li></a>
								</ul>

								<?php if($_SERVER['REQUEST_URI'] == '/post'){
							} else { ?>
							<input type="hidden" id="session" value="1">
							<!-- <div class="col-xs-3"> -->
								<a href="<?php echo base_url('post'); ?>" data-toggle="tooltip" data-placement="bottom" title="Upload News" class="btn btn-default top-nav top-set">
									<div class="top-upload"></div><!-- <i class="glyphicon glyphicon-cloud-upload"></i> -->
								</a>
							<!-- </div> -->
							<?php } ?>
							<!-- <a href="<?php echo base_url('dashboard'); ?>" class="btn btn-default top-nav"><i class="glyphicon glyphicon-home"></i> Dashboard</a> -->
							<a href="" id="logout" class="btn btn-default top-nav top-set" data-toggle="tooltip" data-placement="bottom" title="Logout"><div class="top-logout"></div></a>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
			<!-- <div class="col-xs-6 text-right">
				<a href="<?php echo base_url('logout'); ?>" class="btn btn-default" id="logout">Logout</a>
			</div> -->
		</div>
	</header>
	<div class="page-wrap">


<div class="feedback-link">	
	<a href="<?php echo base_url('feedback'); ?>"></a>
</div>

<div class="modal fade" id="login-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header" style="padding:0px;">
    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
    				<img src="<?php echo base_url('assets/images/delete.png'); ?>" style="cursor:pointer; position:absolute; top:8px; right:8px; width:25px;" data-dismiss="modal">
    			</div>
    		</div>
      		<div class="modal-body" ng-controller="HeaderCtrl">
      			<!-- <img src="<?php echo base_url('assets/images/loading.GIF'); ?>" alt="" style="width:20px; height:20px; margin-left:auto; margin-right:auto; display:block;"> -->
      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
	      			The Username or Password you have entered is incorrect.
      			</div>
      		</div>
      	</div>
    </div>
</div>

<div class="modal fade" id="ban-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header" style="padding:0px;">
    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
    			</div>
    		</div>
      		<div class="modal-body" ng-controller="HeaderCtrl">
      			<!-- <img src="<?php echo base_url('assets/images/loading.GIF'); ?>" alt="" style="width:20px; height:20px; margin-left:auto; margin-right:auto; display:block;"> -->
      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
	      			The User has been banned. Please contact Admin.
      			</div>
      		</div>
      	</div>
    </div>
</div>

<div class="modal fade" id="waiting-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="false">
  	<div class="modal-dialog">
    	<div class="modal-content">
    		<div class="modal-header" style="padding:0px;">
    			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
    				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
    			</div>
    		</div>
      		<div class="modal-body" ng-controller="HeaderCtrl">
      			<img src="<?php echo base_url('assets/images/loading.GIF'); ?>" alt="" style="width:20px; height:20px; margin-left:auto; margin-right:auto; display:block;">
      			<div class="message" style="margin-left:auto; margin-right:auto; height:auto; padding-left:20px; padding-right:20px; text-align:center; font-size:18px;">
	      			{{message.message}}
      			</div>
      		</div>
      	</div>
    </div>
</div>
</div>

<div class="modal fade" id="loading-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
		    <div class="gif">
  				<img src="<?php echo base_url('assets/images/loading.GIF'); ?>" style="width:40px; height:40px; margin-right:auto; margin-left:auto; display:block;" alt="">
  			</div>
			<div class="modal-body" style="text-align:center;">
			</div>
  			<br>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="modal fade" id="loadingError-modal">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="head" style="width:100%; height:70px; background-color:#c50000; padding:10px;">
				<img src="<?php echo base_url('assets/images/logo.png'); ?>" alt="">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
			</div>
			<div class="modal-body" style="text-align:center;">
				<p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Consectetur neque nam porro est odit maiores numquam placeat veniam, voluptas error, iusto ipsam suscipit, reprehenderit eligendi expedita fugiat quasi assumenda nostrum.</p>
			</div>
		    <div class="gif">
  				<!-- <img src="<?php echo base_url('assets/images/loading.GIF'); ?>" style="width:40px; height:40px; margin-right:auto; margin-left:auto; display:block;" alt=""> -->
  				<button style="background-color:#c50000;" onclick="location.reload()">Refresh</button>
  			</div>
  			<br>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div><!-- /.modal -->

<div class="site-footer">
<footer id="top-footer">
	<div class="container">
		<div class="col-lg-6 footer-desc">
			<img src="<?php echo base_url('assets/images/logo.png'); ?>">
			<div class="footer-about-desc">
				MojoScribe is a web & mobile platform which provides everyday citizens with the fortuity to be a part of a global community. It allows users to upload relevant media that affects masses that has the potential to become ground breaking news and is a resource to aggregate newsworthy content from all the corners of the world
			</div>
		</div>
		<div class="col-lg-2 footer-links">
			<h5><strong>Pages</strong></h5>
			<a href="<?php echo base_url(''); ?>" class="footer-links">Home</a>
			<br>
			<a href="<?php echo base_url('poll'); ?>" class="footer-links">Polls</a>
			<!-- <br> -->
			<!-- <a href="" class="footer-links">Results</a> -->
			<br>
			<a href="<?php echo base_url('page/about'); ?>" class="footer-links">About MojoScribe</a>
			<br>
			<a href="<?php echo base_url('page/contact'); ?>" class="footer-links">Contact MojoScribe</a>
		</div>

		<div class="col-lg-2 footer-links" ng-controller="HeaderCtrl">
			<h5><strong>News Categories</strong></h5>
			<div class="cat" ng-repeat="category in categories| limitTo:5">
				<a href="<?php echo base_url(''); ?>categories?categId={{category.id}}" class="footer-links">{{category.name}}</a>
				<br>
			</div>

			<div class="cat">
				<a href="<?php echo base_url('page/categories'); ?>" class="footer-links">more...</a>
				<br>
			</div>
<!-- 			<a href="" class="footer-links">Lifestyle</a>
			<br>
			<a href="" class="footer-links">Politics</a>
			<br>
			<a href="" class="footer-links">Medical</a>
			<br>
			<a href="" class="footer-links">Technology</a> -->
		</div>

		<div class="col-lg-2 footer-links">
			<h5><strong>Live Feeds</strong></h5>
			<a href="<?php echo base_url('rss/feed'); ?>" target="_blank" class="footer-links">RSS</a>
			<br>
			<a href="http://www.twitter.com/mojoscribe" target="_blank" class="footer-links">Twitter</a>
		</div>

	</div>

	<div class="container">
		<div class="row">
			<ul class="footer-social">
				<li class="meta-block">
					<a href="http://www.facebook.com/mojoscribe" target="_blank"><img src="<?php echo base_url('assets/images/fb.png'); ?>" alt=""></a>
				</li>

				<li class="meta-block">
					<a href="https://plus.google.com/+MojoScribe/" target="_blank"><img src="<?php echo base_url('assets/images/gplus.png'); ?>" alt=""></a>
				</li>

				<li class="meta-block">
					<a href="http://www.twitter.com/mojoscribe" target="_blank"><img src="<?php echo base_url('assets/images/twitter.png'); ?>" alt=""></a>
				</li>

				<li class="meta-block">
					<a href=""><img src="<?php echo base_url('assets/images/android-btn.png'); ?>" style="width:120px;" alt=""></a>
				</li>
			</ul>
		</div>
	</div>

</footer>

<footer id="bottom-footer">
	<div class="container">
		<div class="col-lg-6 copyrights">
			Copyright (c) 2014 | All rights reserved | <a href="<?php echo base_url('privacy'); ?>" target="_blank" class="footer-links">Privacy Policy</a> | <a href="<?php echo base_url('terms'); ?>" target="_blank" class="footer-links">Terms and Conditions</a>
		</div>
		<div class="col-lg-6 powered">
			<div class="pull-right">
				Developed by <a href="http://sudosaints.com" target="_blank">SudoSaints</a> , Pune 
			</div>
		</div>
	</div>
</footer>
</div>

<?php if(isset($styles)) {
	foreach($styles as $style) { ?>
		<link rel="stylesheet" href="<?php echo base_url('assets/' . $style ); ?>">
	<?php }
} ?>

<script type="text/javascript">
window.onbeforeunload = function(e){
  gapi.auth.signOut();
};
</script>

<script type="text/javascript">var baseUrl =   '<?php echo base_url(); ?>';</script>
<script type="text/javascript" src="<?php echo base_url('assets/js/jquery.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/jquery-ui.js"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/bootstrap.min.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/thumbelina.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/slideDownMenus.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/video.js'); ?>"></script>

<script>
/*video js stuff*/
videojs('#videoId', { nativeControlsForTouch: false });
</script>

<script type="text/javascript" src="<?php echo base_url('assets/js/angular.min.js'); ?>"></script>
<script type="text/javascript" src="<?php echo base_url('assets/js/angular-sanitize.js'); ?>"></script>

<script type="text/javascript" src="<?php echo base_url('assets/js/loading.js'); ?>"></script>

<script src="<?php echo base_url('assets/js/regLogin.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/controllers/poll.js'); ?>"></script>
<!-- <script src="//vjs.zencdn.net/4.6/video.js"></script> -->
<script src="<?php echo base_url('assets/js/controllers/headercontroller.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/socialLogin/fb.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/socialLogin/gPlus.js'); ?>"></script>
<script src="//maps.googleapis.com/maps/api/js?v=3.exp&sensor=false&libraries=places"></script>
<script src="<?php echo base_url('assets/js/nav-menu.js'); ?>"></script>

<!--<script src="<?php echo base_url('assets/select2/select2.min.js'); ?>"></script> -->
<!--<script src="<?php echo base_url('assets/angular-select2/src/select2.js'); ?>"></script> -->


<script src="<?php echo base_url('assets/js/validations/functions.js'); ?>"></script>

<?php if(isset($scripts)) {

foreach($scripts as $script) { ?>
	<script type="text/javascript" src="<?php echo base_url('assets/js/' . $script); ?>"></script>
<?php }
}
?>

<script>
	document.addEventListener("click",function(event){
		if(event.srcElement.localName == "a"){
			var target = $(event.target);
			console.log(target);
			if(target[0].href.indexOf('http://localhost/mojoscribe') == 0){
				target.attr("target","_blank");
			}
		}
	})
</script>


</body>
</html>
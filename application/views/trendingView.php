<div ng-controller="HeaderCtrl" id="trendingView">
	<?php if(!isset($_SESSION['id'])){ ?>
	<div class="side-bar-hashtags">
		<div class="recent-label"></div>
		<div class="newsType label"><a href="" ng-click="trendingFocus()">Trending</a></div>

		<div class="breaking-label">
		</div>
		<div class="newsType label"><a href="" ng-click="breakingFocus()">Breaking</a></div>

		<div class="mojopicks-label">
		</div>
		<div class="newsType label"><a href="" ng-click="mojoFocus()">MoJo Picks</a></div>

	</div>
	<?php } ?>
	<div class="trending-inner" style="position: relative;">
		<div class="triangle-padding actual-trending-menu">
			<div class="btn btn-default trendingHashtags" ng-mouseenter="menu()" ng-mouseout="menu()">
				#
			</div>
		</div>

		<div class="trendTriangle actual-trending-menu" ng-show="trendingClicked" ng-mouseenter="menu()" ng-mouseout="menu()"></div>
	</div>
	<div class="trending-menu actual-trending-menu" ng-show="trendingClicked" ng-mouseover="menu()" ng-mouseout="menu()">
		<h3 style="color:#c50000;">Trending Tags</h3>
		<div class="trending-item" ng-repeat="trend in trending">
			<div class="trending-bar"></div>
			<div class="trending-text">
				<a href="<?php echo base_url(''); ?>search?q={{trend.name}}">#{{trend.name}}</a>
			</div>
		</div>
	</div>
</div>
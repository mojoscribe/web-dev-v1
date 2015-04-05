<div class="container">
	<div class="row">
		<div class="col-lg-6">
			<h2>&nbsp;</h2>
			<h2>Anonymous Posts</h2>
		</div>
	</div>

	<div class="row" ng-controller="AnonymousCtrl">
		<div class="col-lg-12">
			<input type="text" class="form-control" ng-model="searchQuery" name="allPostSearch" placeholder="Enter Search query here">
			<table class="table" id="anonymous" ng-table="tableParams">
				
				<tbody>
					<tr ng-repeat="post in $data | filter:searchQuery" style="text-align:center;">
						<td data-title="'Serial'" sortable="'serial'">{{post.serial}}</td>
						<td data-title="'Title'" sortable="'title'"><a href="<?php echo base_url(); ?>single/{{post.slug}}" target="_blank" style="text-decoration:none;">{{post.title}}</a>
							<span ng-show="post.removed">(Removed)</span>
							<span ng-show="post.unpublished">(Unpublished)</span>
							<span ng-show="post.breaking">(Breaking)</span>
							<span ng-show="post.featured">(Featured)</span>
						</td>
						<td data-title="'PostType'" sortable="'mediaType'">{{post.mediaType}}</td>
						<td data-title="'PostDate'" sortable="'date'">{{post.date}}</td>
						<td data-title="'Category'" sortable="'categoryName'">{{post.categoryName}}</td>
						<td data-title="'Impact'" sortable="'impactName'">{{post.impactName}}</td>
						<td data-title="'Author'" sortable="'author'">{{post.author}}</td>
						<td data-title="'Shares'" sortable="'shares'">{{post.shares}}</td>
						<td data-title="'Views'" sortable="'views'">{{post.views}}</td>
						<td data-title="'Flags'" sortable="'flags'">{{post.flags}}</td>
						<td data-title="'Rating'" sortable="'rating'">{{post.rating}}</td>
						<td>
						<div class="btn-group">
							<button type="button" class="btn btn-primary dropdown-toggle" data-toggle="dropdown">Action</button>
						    <ul class="dropdown-menu">
						    	<li>
						    		<a href="" ng-click="makeBreaking(post)" ng-hide="post.breaking">
						    			<i class="glyphicon glyphicon-asterisk"></i>
						    			Add to Breaking News
						    		</a>

						    		<a href="" ng-click="removeBreaking(post)" ng-show="post.breaking">
						    			<i class="glyphicon glyphicon-asterisk"></i>
						    			Remove from Breaking News
						    		</a>
						    	</li>

						    	<li>
						    		<a href="" ng-click="makeFeatured(post)" ng-hide="post.featured">
						    			<i class="glyphicon glyphicon-star"></i>
						    			Add to MojoPicks
						    		</a>

						    		<a href="" ng-click="removeFeatured(post)" ng-show="post.featured">
						    			<i class="glyphicon glyphicon-star"></i>
						    			Remove from MojoPicks
						    		</a>
						    	</li>

						    	<li>
						    		<a href="#categoryModal" data-toggle="modal" ng-click="singlePost(post)">
						    			<i class="glyphicon glyphicon-th-list"></i>
						    			Change Category
						    		</a>
						    	</li>

						    	<li>
						    		<a href="#impactModal" data-toggle="modal" ng-click="singlePost(post)">
						    			<i class="glyphicon glyphicon-list-alt"></i>
						    			Change Impact
						    		</a>
						    	</li>

						    	<li>
						    		<a href="" ng-click="unpublish(post)" ng-hide="post.unpublished">
						    			<i class="glyphicon glyphicon-remove"></i>
						    			Unpublish Post
						    		</a>

						    		<a href="" ng-click="publish(post)" ng-show="post.unpublished">
						    			<i class="glyphicon glyphicon-remove"></i>
						    			Publish Post
						    		</a>
						    	</li>


						    	<li>
						    		<a href="" ng-click="remove(post)" ng-hide="post.removed">
						    			<i class="glyphicon glyphicon-trash"></i>
						    			Remove Post
						    		</a>

						    		<a href="" ng-click="approve(post)" ng-show="post.removed">
						    			<i class="glyphicon glyphicon-trash"></i>
						    			Approve Post
						    		</a>
						    	</li>
						    </ul>
						</div>
					</tr>	
				</tbody>
			</table>
		</div>

		<div class="modal fade" id="impactModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			    <div class="modal-content">
			    	<form action="" ng-submit="saveImpact(single)">
			    	<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				    </div>
			    	<div class="modal-body">
						<p>Thanks for rating the post</p>
						Please select impact of this post
						<select name="" id="" ng-model="impact" class="form-control">
							<option ng-repeat="impac in impacts" value="{{impac.id}}">It has a {{impac.name}} Impact</option>
						</select>
						<br>
				        <input type="submit" class="btn btn-default" style="float:right;" ng-click="changeImpact(single)" value="Done!" data-dismiss="modal">
			      		<br>
			      	</div>
				    </form>
			    </div>
			</div>
		</div>

		<div class="modal fade" id="categoryModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
			<div class="modal-dialog">
			    <div class="modal-content">
			    	<form action="" ng-submit="saveCategory(single)">
			    	<div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				        
				    </div>
			    	<div class="modal-body">
						<p>Thanks for rating the post</p>
						Please select Category of this post
						<select name="" id="" ng-model="category" class="form-control">
							<option ng-repeat="categ in categories" value="{{categ.id}}">{{categ.name}}</option>
						</select>
						<br>
				        <input type="submit" class="btn btn-default" style="float:right;" ng-click="changeCategory(single)" value="Done!" data-dismiss="modal">
			      		<br>
			      	</div>
				    </form>
			    </div>
			</div>
		</div>
	</div>

	

</div>
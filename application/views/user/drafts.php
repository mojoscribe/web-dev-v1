
		<div class="col-lg-9" ng-controller="DraftsCtrl">
			<div class="drafts-button-panel">
				<a class="btn btn-default col-lg-3 delete-selected" ng-click="deletePosts()"><i class="glyphicon glyphicon-trash"></i> Delete Selected</a>
				<a class="btn btn-default col-lg-3 publish-selected" ng-click="publishPosts()"><i class="glyphicon glyphicon-eye-open"></i> Publish Selected</a>
				<select class="pull-right drafts-page" ng-change="getAll()" ng-model="selectBox">
					<option value="5">Show 5 Posts</option>
					<option value="10">Show 10 Posts</option>
					<option value="20">Show 20 Posts</option>
					<option value="50">Show 50 Posts</option>
					<option value="100">Show 100 Posts</option>
				</select>
			</div>

			<div id="drafts-container">
				<div class="no-posts-message" ng-show="empty" style="text-align:center;">
					<h3>There are no posts to display for this user.</h3>
				</div>

				<div class="bs-component" style="margin-bottom:20px;" ng-hide="empty">
					<table class="table table-striped">
						<thead>
							<tr>
								<th class="drafts-table col-lg-5"><input type="checkbox" id="selectAll" ng-click="selectAll()" ng-model="allChecked" class="drafts-table col-lg-1"><div class="drafts-table-title col-lg-10">Title</div></th>
								<th class="drafts-table col-lg-3"><i class="glyphicon glyphicon-tags"></i></th>
								<th class="drafts-table col-lg-2">Date</th>
								<th class="drafts-table col-lg-2">Publish</th>
							</tr>
						</thead>
						<tbody>
							<tr ng-repeat="draft in drafts">
								<td class="drafts-table col-lg-5">
									<input type="checkbox" ng-model="checked[$index]" ng-click="singleChecked($index)" class="drafts-table col-lg-1">
									<div class="drafts-table-title col-lg-10">
										<a href="<?php echo base_url('drafts/editDraftView?id='.'{{draft.id}}'); ?>">
											{{draft.title}}
										</a>
										<span class="pull-right" ng-show="draft.isAnonymous">
											Anonymous
										</span>
									</div>
								</td>
								<td class="drafts-table col-lg-3"><span ng-repeat="hashtag in draft.hashtags">#{{hashtag.name}}, </span></td>
								<td class="drafts-table col-lg-2">{{draft.updatedOn}}</td>
								<td class="drafts-table col-lg-2"><a href="#publish-check-modal" ng-model="singleDraft" data-toggle="modal" ng-click="singleDraft(draft)" class="btn btn-default col-lg-12 rounded button"><strong><i class="glyphicon glyphicon-eye-open"></i> Publish</strong></a></td>
							</tr>
						</tbody>
					</table>
				</div>
			</div>
			<div id="publish-check-modal" class="modal fade in" tabindex="-1" role="dialog"  aria-hidden="false">
				<div class="modal-dialog">
				    <div class="modal-content">

				      <div class="modal-header">
				        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
				        <h4 class="modal-title">Publish Post</h4>
				      </div>

				      <div class="modal-body">
				      	<div class="row">
			      			<div class="col-lg-12">
			      				<h4 style="text-align: center;">Are you sure you want to Publish?</h4>
			      			</div>
			      		</div>
				      </div>
				      <div class="modal-footer">
				      	<a href="<?php echo base_url()."drafts/publishSingleDraft?id={{currentDraft.id}}"; ?>" ng-click="publishDraft()" type="button" class="btn btn-default col-lg-5">Yes</a>
			        	<a type="button" class="btn btn-default col-lg-5" style="float:right;" data-dismiss="modal">No</a>
				      </div>

				    </div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container page-container" ng-controller="ContactCtrl"> 
	<div class="row">
		<div class="col-lg-12">
			<h2>Contact Us</h2>
			<hr>
			<div class="contact-content">
				<p><bold> Get in Touch! </bold>
Let's be amici! <a href="http://www.facebook.com/mojoscribe" target="_blank">Like us on Facebook</a>, or <a href="http://www.twitter.com/mojoscribe" target="_blank">Follow us on Twitter</a>. Comment, Message or Tweet us your questions, or you can use the Form below.
We are here to answer any questions you may have about your MojoScribe experience. Reach out to us, and we will respond as soon as we can.
Even if there is something you feel we should do better, or you just want to let us know about your thoughts, please don't hesitate to contact us.</p>
				<div class="col-lg-6 col-lg-offset-3">
					<div class="error" id="error">
						<p>Fields marked with * are mandatory.</p>
					</div>
					<form action="" class="form" ng-submit="submitForm()">
						<div class="form-group">
							<label for="name">Your Name *</label>
							<input type="text" id="name" ng-model="form.name" class="form-control" name="name">
						</div>
						<div class="form-group">
							<label for="email">Your Email *</label>
							<input type="text" id="email" ng-model="form.email" class="form-control" name="email">
						</div>
						<div class="form-group">
							<label for="phone">Your Phone</label>
							<input type="text" id="phone" ng-model="form.phone" class="form-control" name="phone">
						</div>
						<div class="form-group">
							<label for="message">Message *</label>
							<textarea name="message" ng-model="form.message" id="message" cols="30" rows="5" class="form-control"></textarea>
						</div>
						<input type="submit" class="btn btn-primary" value="Send Message"  style="background-color: #C50000; margin-top:15px; margin-bottom:50px; font-size:14px; border-radius:0px; width:140px; border-color:#c50000;" />
					</form>
				</div>
			</div>
		</div>
	</div>
</div>
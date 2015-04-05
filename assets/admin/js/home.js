$(document).ready(function(){
	$('.posts .post').mouseenter(function(){
		var $this = $(this).find('.hidden');
		//alert($this.html());
		$(this).children('.post-attachment').slideToggle(200,function(){
			$this.show();
		});
	});
	
	$('.posts .post').mouseleave(function(){
		var $this = $(this).find('.hidden');
		$(this).children('.post-attachment').slideToggle(200,function(){
			$this.hide();
		});
	});
	
	$(".tooltipTextRight").popover({placement:'right'});
	
	$("#subscribe-btn").click(function(e){
		var email = $("#subscribe-mail").val();
		if(email == ""){
			alert("Email field cannot be blank.");
			return false;
		}
		var pattern = /^\w+@[a-zA-Z_]+?\.[a-zA-Z]{2,3}$/;
		if(!email.match(pattern)){
			alert("Invalid Email Address");
			return false;
		}
		$.ajax({
			url: base_url + 'subscribe/subscribe',
			data: {email: email},
			type: 'POST',
			success: function(response){
				console.log(response);
				if(response.success){
					alert("Subscribed Successfully!");
				} else {
					alert("There was some issue while subscribing you!");
				}
			},
			error: function(x){
				console.log("Error: " + x);
			}
		});
		$("#subscribe-mail").val("");
	});
});
var htmlarea;
var selectedColor = "#1abc9c";
$(document).ready(function(){
	var articleId = decodeURI((RegExp('id=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
	var attachmentHTML = '';
	$("#edit-article").htmlarea();	
	
	$("#approve-article").click(function(e){
		e.preventDefault();
		//updateArticle('PUBLISHED');
		var data = {status: 'PUBLISHED', id: articleId};
		$.ajax({
			url: base_url + '/article/publish',
			type: 'POST',
			data: data,
			success: function(resp){
				//console.log(resp);
				if(resp.success){
					window.location = base_url;
				}
			}
		});
	});
	
	$("#suggest-change").click(function(e){
		e.preventDefault();
		var suggestion = $("#suggestion").val();
		var data = {id: articleId,suggestion: suggestion};
		$.ajax({
			url: base_url + '/articles/saveSuggestion',
			type: 'POST',
			data: data,
			success: function(resp){
				alert('Suggestion Submitted!');
			}
		});
	});
	
	
	$(".color").click(function(e){
		e.preventDefault();
		$(".color").each(function(){
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');
		selectedColor = "#" + $(this).attr('id');
	});
	
	var autoSave = function(){
		console.log('Saving..');
	}
	
	
});
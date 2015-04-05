

var htmlarea;
var selectedColor = "#1abc9c";
$(document).ready(function(){
	//var scribbleId = 0;
	var scribbleId = decodeURI((RegExp('id=' + '(.+?)(&|$)').exec(location.search)||[,null])[1]);
	if(scribbleId == 'null') {
		scribbleId = 0;
	}
	var attachmentHTML = '';
	
	$("#scribble-title").focusout(function(){
		if(scribbleId == 0) {
			saveScribble('NEW');
		}
	});
	$("#edit-scribble").htmlarea({
		toolbar: [
		          	["bold", "italic", "underline", "strikethrough", "|", "subscript", "superscript"],
			        ["increasefontsize", "decreasefontsize"],
			        ["orderedlist", "unorderedlist"],
			        ["indent", "outdent"],
			        ["justifyleft", "justifycenter", "justifyright"],
			        ["link", "unlink", "image", "horizontalrule"],
			        ["p", "h1", "h2", "h3", "h4", "h5", "h6"],
			        ["cut", "copy", "paste"],
			        [
		              {
		                  // The CSS class used to style the <a> tag of the toolbar button
		                  css: 'add-attachment',

		                  // The text to use as the <a> tags "Alt" attribute value
		                  text: 'Add/Insert Attachment file',

		                  // The callback function to execute when the toolbar button is clicked
		                  action: function (btn) {
		                      // 'this' = jHtmlArea object
		                      // 'btn' = jQuery object that represents the <a> ("anchor") tag for the toolbar button
		                	  $('#myModal').modal('show');
		                	  var txtArea = this;
		                	  $("#insert-into-post").click(function(e){
		                			e.preventDefault();
		                			$('#myModal').modal('hide');
		                			txtArea.pasteHTML(attachmentHTML);
		                	  });
		                  }
		              }
		             ]
		      ]
	});
	$('.jHtmlArea iframe').css({
		border: '1px solid #ccc'
	});
	
	$('#attachment-form-submit').click(function(e){
		e.preventDefault();
		uploadFile();
	});
	
	$(".insert-attachment").click(function(e){
		e.preventDefault();
		$('.add-attachment').trigger('click');
	});
	
	$("#submit-scribble").click(function(e){		
		e.preventDefault();
		saveScribble('ADMIN_REVIEW');
		
	});
	
	$("#save-draft").click(function(e){
		e.preventDefault();
		saveScribble('DRAFT');
	});
	
	$(".color").click(function(e){
		e.preventDefault();
		$(".color").each(function(){
			$(this).removeClass('selected');
		});
		$(this).addClass('selected');
		selectedColor = "#" + $(this).attr('id');
	});
	
	function uploadFile() {
		$(".attachment-upload-form").css('opacity','0.7');
		$("#progress-bar-parent").show();
		var data = new FormData();
		data.append('file',document.getElementById("attachment").files[0]);
		data.append('attachment-type',$('#attachment-type').val());
		data.append('scribble-id',scribbleId);
		var xhr = new XMLHttpRequest();
		xhr.upload.addEventListener('progress', function(e) {
			var percentage = 0;
			if (e.lengthComputable) {
				percentage = Math.round((e.loaded * 100) / e.total);
			}
	        //console.log(percentage);
	        $("#upload-progress").css("width","" + percentage + "%");
	    }, false);
		
		xhr.open("POST", base_url + "/scribble/upload");  
		xhr.send(data);
		xhr.onreadystatechange=function(){
			if (xhr.readyState==4 && xhr.status==200){
				$(".attachment-upload-form").css('opacity','1');
				$("#progress-bar-parent").hide();
				//console.log(xhr.responseText);
				var response = $.parseJSON(xhr.responseText);
				//console.log(response.data);
				attachmentHTML = response.data.attachmentHTML;

				$("#insert-into-post").show();
			}
		}
	}
	
	function saveScribble(status){
		var title = $("#scribble-title").val();
		var content = $("#edit-scribble").val();
		// var excerpt = $("#excerpt").val();
		
		if(scribbleId == 0) {
			var data = {title: title, content: content};
		} else {
			var data = {title: title, content: content};
		}
		
		
	}
	var autoSave = function(){
		console.log('Saving..');
	}
	var color = $("#color-val").val();
	console.log(color);
	
	if($("#color-val").val() != "") {
		$('.color').each(function(){
		});
	}
}); //end of doc ready
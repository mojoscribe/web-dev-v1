// function processCategoryDate(e){e.data.forEach(function(e){e.posts.forEach(function(e){var t=(new Date).getTimezoneOffset();var n=new Date(e.date+"+00:00 UTC");e.date=n.toDateString()})});return e}function processDate(e){e.data.forEach(function(e){var t=(new Date).getTimezoneOffset();var n=new Date(e.date+"+00:00 UTC");e.date=n.toDateString()});return e}function processBreakingDate(e){e.forEach(function(e){var t=(new Date).getTimezoneOffset();var n=new Date(e.date+"+00:00 UTC");e.date=n.toDateString()});return e}

function processCategoryDate(e) {
    e.data.forEach(function(e) {
        e.posts.forEach(function(e) {
	    	var date = e.date.split(/[- :]/);
			var t = date[0] + "/" + date[1] + "/" + date[2] + " " + date[3] + ":" + date[4] + ":" + date[5];
			var n = new Date(t + " +00:00 UTC");
	        
	        e.date = n.toDateString();
         })
    });
    return e
}

function processDate(e) {
    e.data.forEach(function(e) {
    	var date = e.date.split(/[- :]/);
		var t = date[0] + "/" + date[1] + "/" + date[2] + " " + date[3] + ":" + date[4] + ":" + date[5];
		var n = new Date(t + " +00:00 UTC");
        
        e.date = n.toDateString();
    });
    return e
}

function processBreakingDate(e) {
    e.forEach(function(e) {
    	var date = e.date.split(/[- :]/);
		var t = date[0] + "/" + date[1] + "/" + date[2] + " " + date[3] + ":" + date[4] + ":" + date[5];
		var n = new Date(t + " +00:00 UTC");
        
        e.date = n.toDateString();
    });
    return e
}
var SearchCtrl = ["$scope", "$http", "$timeout", function(e, t, n) {
    e.searchResults = [];
    e.q = $("#searchQuery").val();
    e.getResults = function() {
        var n = baseUrl + "search/getPosts?q=" + e.q;
        t.get(n).success(function(t) {

            if (t.data == null || t.data == "" || typeof(t.data.data) == "string") {
                e.empty = true
            } else {
                var n = processBreakingDate(t.data.data);
                e.searchResults = n
            }
        })
    };
    n(function() {
        e.getResults()
    }, 50)
}]
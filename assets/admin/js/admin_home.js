Morris.Line({
  // ID of the element in which to draw the chart.
  element: 'morris-chart-line',
  // Chart data records -- each entry in this array corresponds to a point on
  // the chart.
  data: [
	{ d: '2013-10-01', visits: 10 },
	{ d: '2013-10-02', visits: 2 },
	{ d: '2013-10-03', visits: 12 },
	{ d: '2013-10-04', visits: 0 },
	{ d: '2013-10-05', visits: 7 },
	{ d: '2013-10-06', visits: 9 },
	{ d: '2013-10-07', visits: 7 },
	{ d: '2013-10-08', visits: 7 },
	{ d: '2013-10-09', visits: 15 },
	{ d: '2013-10-10', visits: 22 },
	{ d: '2013-10-11', visits: 12 },
	{ d: '2013-10-12', visits: 5 },
	{ d: '2013-10-13', visits: 7 },
	{ d: '2013-10-14', visits: 15 },	
  ],
  // The name of the data record attribute that contains x-visitss.
  xkey: 'd',
  // A list of names of data record attributes that contain y-visitss.
  ykeys: ['visits'],
  // Labels for the ykeys -- will be displayed when you hover over the
  // chart.
  labels: ['Articles Submitted'],
  // Disables line smoothing
  smooth: false,
});
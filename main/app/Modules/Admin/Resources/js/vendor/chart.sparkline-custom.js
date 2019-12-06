$(function(){
	// Line Chart 1
	$('#sparkline1').sparkline('html', {
		type: 'line',
		width:200,
		height:70,
	    lineColor: '#f90a48',
	    fillColor: false,
	    spotColor: '#0000ff',
	    minSpotColor: '#0000ff',
	    maxSpotColor: '#0000ff'
	});	

	// Line Chart 2
	$('#sparkline2').sparkline('html', {
		type: 'line',
		width:200,
		height:70,
	    lineColor: '#ad0af9',
	    lineWidth: 2,
	    spotRadius: 2.5,
	    fillColor: false,
	    spotColor: '#f90a48',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});

	// Area Chart
	$('#sparkline3').sparkline('html', {
		type: 'line',
		width:200,
		height:70,
	    lineColor: '#ad0af9',
	    lineWidth: 2,
	    spotRadius: 2.5,
	    fillColor: 'rgba(173,10,249,0.3)',
	    spotColor: '#f90a48',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});

	// Bar Chart 1
	$('#sparkline4').sparkline('html', {
		type: 'bar',
		width:200,
		height:70,
	    barWidth: 10,
    	barSpacing: 5,
    	barColor: '#f90a48',
	  	negBarColor: '#ccc',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});

	// Bar Chart 2
	$('#sparkline5').sparkline('html', {
		type: 'bar',
		width:200,
		height:70,
	    barWidth: 10,
    	barSpacing: 5,
    	barColor: '#f90a48',
	  	negBarColor: '#333',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});

	// Bar Chart Stacked
	$('#sparkline6').sparkline('html', {
		type: 'bar',
		height: 70,
		barWidth: 10,
		barSpacing: 5,
		barColor: '#f90a48',
		chartRangeMax: 12
	});

	$('#sparkline6').sparkline([3,2,7,8,4,5,8,3,6,4,4,9,6,4,7], {
		type: 'bar',
		composite: true,
		height: 70,
		barWidth: 10,
		barSpacing: 5,
		barColor: '#333',
		chartRangeMax: 12
	});

	// Bar Chart discrete
	$('#sparkline7').sparkline('html', {
		type: 'discrete',
		width:200,
		height:70,
    	lineColor: '#f90a48'
	});

	// Pie Chart
	$('#sparkline8').sparkline('html', {
		type: 'pie',
		width:200,
		height:70,
	    sliceColors: ['#f90a48','orange', '#ad0af9','green']
	});

	// Bullet Chart
	$('#sparkline9').sparkline('html', {
		type: 'bullet',
		width:200,
		height:70,
		targetColor: '#fff',
		performanceColor: '#f90a48',
		rangeColors: ['#ad0af9','yellow','green']
	});
});
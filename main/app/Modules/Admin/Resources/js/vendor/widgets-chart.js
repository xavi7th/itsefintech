$(function(){
	// Line Chart 1
	$('#sparkline1').sparkline('html', {
		type: 'line',
		width:100,
		height:50,
	    lineColor: '#f90a48',
	    fillColor: false,
	    spotColor: '#0000ff',
	    minSpotColor: '#0000ff',
	    maxSpotColor: '#0000ff'
	});		

	// Area Chart
	$('#sparkline2').sparkline('html', {
		type: 'line',
		width:100,
		height:50,
	    lineColor: '#ad0af9',
	    lineWidth: 2,
	    spotRadius: 2.5,
	    fillColor: 'rgba(173,10,249,0.3)',
	    spotColor: '#f90a48',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});	

	// Bar Chart
	$('#sparkline3').sparkline('html', {
		type: 'bar',
		width:100,
		height:50,
	    barWidth: 10,
    	barSpacing:3,
    	barColor: '#f90a48',
	  	negBarColor: '#ccc',
	    minSpotColor: '#f90a48',
	    maxSpotColor: '#f90a48'
	});

	// Pie Chart
	$('#sparkline4').sparkline('html', {
		type: 'pie',
		height:110,
	    sliceColors: ['#f90a48','orange']
	});

  
	// Chart Donut
	new Morris.Donut({
		element: 'morris-donut',
		data: [
		     {label: "Buying", value: 12},
		     {label: "Selling", value: 30},
		     {label: "Balance", value: 20}
		   ],
		   colors: ['#f90a48','#34aff6','#ccc'],
		   resize: true
	});

	// morris line
	new Morris.Line({
	    element: 'morris-line',
	    data: [
	        { y: '2010', a: 24},
	        { y: '2011', a: 50},
	        { y: '2012', a: 90},
	        { y: '2013', a: 40},
	        { y: '2014', a: 120},
	        { y: '2015', a: 100},
	        { y: '2016', a: 160},
	        { y: '2017', a: 140},
	        { y: '2018', a: 100},
	    ],
	    xkey: 'y',
	    ykeys: ['a'],
	    labels: ['Buying'],
	    lineColors: ['#f90a48'],
	    lineWidth: 2,
	    grid : false,
	    axes: false,
	    gridTextSize: 10,
	    padding:1,
	    hideHover: 'auto',
	    resize: true
	});

	// morrris bar
	new Morris.Bar({
	    element: 'morris-bar',
 		barSizeRatio:0.5,
	    data: [
	        { y: '2010', a: 20, b: 50,},
	        { y: '2011', a: 20, b: 30,},
	        { y: '2012', a: 30, b: 20,},
	        { y: '2013', a: 15, b: 30,},
	        { y: '2014', a: 42, b: 54,},
	        { y: '2015', a: 33, b: 29,},
	        { y: '2015', a: 54, b: 66,},
	        { y: '2017', a: 14, b: 28,}
	    ],
	    xkey: 'y',
	    ykeys: ['a', 'b'],
	    labels: ['Buying', 'Selling'],
	    barColors: ['#f90a48', 'orange'],
	    lineWidth: 0,
	    grid : false,
	    padding:-15,
	    axes: false,
     	fillOpacity: 0.8,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
	});		

	// morris line
	new Morris.Area({
	    element: 'morris-area',
	    data: [
	        { y: '2010', a: 24},
	        { y: '2011', a: 50},
	        { y: '2012', a: 90},
	        { y: '2013', a: 40},
	        { y: '2014', a: 120},
	        { y: '2015', a: 100},
	        { y: '2016', a: 160},
	        { y: '2017', a: 140},
	        { y: '2018', a: 100},
	    ],
	    xkey: 'y',
	    ykeys: ['a'],
	    labels: ['Buying'],
	    lineColors: ['#ad0af9'],	
	    fillOpacity: 0.3,
	    lineWidth: 2,
	    grid : false,
	    axes: false,
	    gridTextSize: 10,
	    padding:1,
	    hideHover: 'auto',
	    resize: true
	});	


	// morris area
    new Morris.Area({
        element: 'morris-area2',
        data: [
            { y: '2010', a: 24, b:32},
            { y: '2011', a: 50, b:64},
            { y: '2012', a: 90, b:102},
            { y: '2013', a: 40, b:45},
            { y: '2014', a: 120, b:132},
            { y: '2015', a: 100, b:110},
            { y: '2016', a: 160, b:170},
            { y: '2017', a: 140, b:150},
            { y: '2018', a: 100, b:117}
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Buying', 'Total'],
        lineColors: ['#ad0af9', 'orange'],  
        fillOpacity: 0.5,
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:1,
        hideHover: 'auto',
        resize: true
    });



    // morris line
    new Morris.Line({
        element: 'morris-line-1',
        data: [
            { y: '8:00 AM', a: 120},
            { y: '10:00 AM', a: 720},
            { y: '12:00 AM', a: 800},
            { y: '1:00 PM', a: 50},
            { y: '3:00 PM', a: 250},
            { y: '5:00 PM', a: 380},
            { y: '7:00 PM', a: 600},
            { y: '9:00 PM', a: 550},
            { y: '11:00 PM', a: 700},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#F7931A'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });

    // morris line
    new Morris.Line({
        element: 'morris-line-2',
        data: [
            { y: '8:00 AM', a: 120},
            { y: '10:00 AM', a: 720},
            { y: '12:00 AM', a: 800},
            { y: '1:00 PM', a: 50},
            { y: '3:00 PM', a: 250},
            { y: '5:00 PM', a: 380},
            { y: '7:00 PM', a: 600},
            { y: '9:00 PM', a: 550},
            { y: '11:00 PM', a: 700},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#282828'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });

    // morris line
    new Morris.Line({
        element: 'morris-line-3',
        data: [
            { y: '8:00 AM', a: 80},
            { y: '10:00 AM', a: 160},
            { y: '12:00 AM', a: 80},
            { y: '1:00 PM', a: 160},
            { y: '3:00 PM', a: 80},
            { y: '5:00 PM', a: 160},
            { y: '7:00 PM', a: 80},
            { y: '9:00 PM', a: 160},
            { y: '11:00 PM', a: 80},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#346AA9'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });    

    // morris line
    new Morris.Line({
        element: 'morris-line-4',
        data: [
            { y: '8:00 AM', a: 50},
            { y: '10:00 AM', a: 150},
            { y: '12:00 AM', a: 700},
            { y: '1:00 PM', a: 200},
            { y: '3:00 PM', a: 100},
            { y: '5:00 PM', a: 205},
            { y: '7:00 PM', a: 70},
            { y: '9:00 PM', a: 180},
            { y: '11:00 PM', a: 60},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#3CC8C8'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });

    // morris line
    new Morris.Line({
        element: 'morris-line-5',
        data: [
            { y: '8:00 AM', a: 80},
            { y: '10:00 AM', a: 160},
            { y: '12:00 AM', a: 80},
            { y: '1:00 PM', a: 160},
            { y: '3:00 PM', a: 80},
            { y: '5:00 PM', a: 160},
            { y: '7:00 PM', a: 80},
            { y: '9:00 PM', a: 160},
            { y: '11:00 PM', a: 80},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#08B5E5'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });

    // morris line
    new Morris.Line({
        element: 'morris-line-6',
        data: [
            { y: '8:00 AM', a: 120},
            { y: '10:00 AM', a: 720},
            { y: '12:00 AM', a: 800},
            { y: '1:00 PM', a: 50},
            { y: '3:00 PM', a: 250},
            { y: '5:00 PM', a: 380},
            { y: '7:00 PM', a: 600},
            { y: '9:00 PM', a: 550},
            { y: '11:00 PM', a: 700},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Value'],
        lineColors: ['#58BF00'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });
});
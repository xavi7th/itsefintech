$(function(){
	// Chart Line 1
	new Morris.Line({
	    element: 'morris-line1',
	    data: [
	        { y: '2006', a: 140, b: 160, c:50 },
	        { y: '2007', a: 24,  b: 54,  c:100 },
	        { y: '2008', a: 88,  b: 118, c:50 },
	        { y: '2009', a: 45,  b: 75,  c:100 },
	        { y: '2010', a: 60,  b: 90,  c:50 },
	    ],
	    xkey: 'y',
	    ykeys: ['a', 'b', 'c'],
	    labels: ['Buying', 'Selling', 'Balance'],
	    lineColors: ['#f90a48', '#34aff6', '#ccc'],
	    lineWidth: 1,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
	});

	// Chart Line 2
	new Morris.Line({
	    element: 'morris-line2',
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
	    lineWidth: 1,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
	});
	
	// Chart Area 1
	new Morris.Area({
	    element: 'morris-area1',
	    data: [
	        { y: '2014', a: 100, b: 120 },
	        { y: '2015', a: 60, b: 80 },
	        { y: '2016', a: 100, b: 120 },
	        { y: '2017', a: 240, b: 220 },
	        { y: '2018', a: 120, b: 140 },
	    ],
	    xkey: 'y',
	    ykeys: ['a', 'b'],
	    labels: ['Buying', 'Selling'],
	    lineColors: ['#f90a48', '#bbb'],
	    lineWidth: 0,
     	fillOpacity: 0.8,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
	});

	// Chart Area 2
	new Morris.Area({
	    element: 'morris-area2',
	    data: [
	        { y: '2013', a: 20, b: 50, c: 65 },
	        { y: '2014', a: 60, b: 30, c: 30 },
	        { y: '2015', a: 30, b: 20, c: 30 },
	        { y: '2016', a: 25, b: 80, c: 40 },
	        { y: '2017', a: 10, b: 30, c: 10 }
	    ],
	    xkey: 'y',
	    ykeys: ['a', 'b', 'c'],
	    labels: ['Buying', 'Selling', 'Balance'],
	    lineColors: ['#f90a48', '#34aff6', '#ccc'],
	    lineWidth: 0,
     	fillOpacity: 0.8,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
	});	

	// Chart Bar
	new Morris.Bar({
	    element: 'morris-bar',
	    data: [
	        { y: '2013', a: 20, b: 50,},
	        { y: '2014', a: 60, b: 30,},
	        { y: '2015', a: 30, b: 20,},
	        { y: '2016', a: 25, b: 80,},
	        { y: '2017', a: 10, b: 30,}
	    ],
	    xkey: 'y',
	    ykeys: ['a', 'b'],
	    labels: ['Buying', 'Selling'],
	    barColors: ['#f90a48', '#ccc'],
	    lineWidth: 0,
     	fillOpacity: 0.8,
	    gridTextSize: 10,
	    hideHover: 'auto',
	    resize: true
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
});
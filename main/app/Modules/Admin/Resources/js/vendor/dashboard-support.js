$(function(){

	// morris area
	new Morris.Area({
	    element: 'morris-area',
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
	    fillOpacity: 0.3,
	    lineWidth: 2,
	    grid : false,
	    axes: false,
	    gridTextSize: 10,
	    padding:1,
	    hideHover: 'auto',
	    resize: true
	});

	    // data table
    $('#datatable1').DataTable({
        responsive: true,
        searching: false,
        bLengthChange: false,
        bPaginate: false,
        bInfo: false
    });           

});
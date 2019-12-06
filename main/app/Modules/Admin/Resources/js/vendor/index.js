$(function(){

    // Area Chart
    $('#sparkline2').sparkline('html', {
        type: 'line',
        width:70,
        height:50,
        lineColor: '#ad0af9',
        lineWidth: 2,
        spotRadius: 2.5,
        fillColor: 'rgba(173,10,249,0.3)',
        spotColor: '#f90a48',
        minSpotColor: '#f90a48',
        maxSpotColor: '#f90a48'
    }); 

    // morris line
    new Morris.Area({
        element: 'morris-area2',
        data: [
            { y: '2010', a: 24, b:32},
            { y: '2011', a: 50, b:64},
            { y: '2012', a: 90, b:102},
            { y: '2013', a: 40, b:45},
            { y: '2014', a: 100, b:110},
            { y: '2015', a: 90, b:100},
            { y: '2016', a: 130, b:220},
            { y: '2017', a: 120, b:130},
            { y: '2018', a: 80, b:100}
        ],
        xkey: 'y',
        ykeys: ['a', 'b'],
        labels: ['Buying', 'Total'],
        lineColors: ['#f90a48', 'orange'],  
        fillOpacity: 0.3,
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:0,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
    });


    // Chart Donut
    var ctx10 = $('#chart-donut');
    new Chart(ctx10, {
        type: 'doughnut',
        data: {
            labels: ["Chrome", "Firefox", "Safari", "Explorer"],
            datasets: [
                {
                    label: "TeamA Score",
                    data: [1250, 970, 570, 290],
                    backgroundColor: [
                        "#f90a48",
                        "#ec9c4f",
                        "#ad0af9",
                        "#57c7d4"
                    ],
                    borderColor: [
                        "#fff",
                        "#fff",
                        "#fff",
                        "#fff"
                    ],
                    borderWidth: [5, 5, 5, 5],
                    hoverBorderColor: 'transparent'
                }
            ]
        },
        options: {
            cutoutPercentage: 80,
            responsive: true,  
            maintainAspectRatio: false,
            title: {
                display: false,
                position: "top",
                text: "Pie Chart",
                fontSize: 18,
                fontColor: "#333"
            },
            legend: {
                display: false,
                position: "bottom",
                labels: {
                    fontColor: "#666",
                    fontSize: 12
                }
            },
        }
    });


    // morris line
    new Morris.Line({
        element: 'morris-line',
        data: [
            { y: '8:00 AM', a: 24},
            { y: '10:00 AM', a: 70},
            { y: '12:00 AM', a: 120},
            { y: '1:00 PM', a: 10},
            { y: '3:00 PM', a: 120},
            { y: '5:00 PM', a: 50},
            { y: '7:00 PM', a: 130},
            { y: '9:00 PM', a: 140},
            { y: '11:00 PM', a: 220},
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Visitor'],
        lineColors: ['#f90a48'],
        lineWidth: 2,
        grid : false,
        axes: false,
        gridTextSize: 10,
        padding:5,
        hideHover: 'auto',
        resize: true
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

    // Bar Chart Stacked
    $('#sparkline5').sparkline('html', {
        type: 'bar',
        height: 70,
        barWidth: 10,
        barSpacing: 5,
        barColor: '#999',
        chartRangeMax: 12
    });
    $('#sparkline5').sparkline([3,2,7,8,4,5,8,3,6,4,4,9,6,4,7], {
        type: 'bar',
        composite: true,
        height: 70,
        barWidth: 10,
        barSpacing: 5,
        barColor: '#333',
        chartRangeMax: 12
    });



    if($(window).width() < 480){
       // re-size sparkline
        $(window).resize(function(e) {
            $('[id*="sparkline"] canvas').css('width','100%');
        }); 
    }

});
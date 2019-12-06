$(function(){
    // data table
    $('#datatable1').DataTable({
        responsive: true,
        searching: false,
        bLengthChange: false,
        bPaginate: false,
        bInfo: false
    });           

    $('#datatable2').DataTable({
        responsive: true,
        searching: false,
        bLengthChange: false,
        bPaginate: false,
        bInfo: false
    });

    // morris line
    new Morris.Area({
        element: 'morris-area',
        data: [
            { y: '2010', a: 10},
            { y: '2011', a: 30},
            { y: '2012', a: 70},
            { y: '2013', a: 30},
            { y: '2014', a: 80},
            { y: '2015', a: 70},
            { y: '2016', a: 100},
            { y: '2017', a: 80},
            { y: '2018', a: 60}
        ],
        xkey: 'y',
        ykeys: ['a'],
        labels: ['Buying'],
        lineColors: ['#f90a48'],  
        fillOpacity: 0.3,
        lineWidth: 2,
        grid : false,
        gridTextColor: '#ccc',
        gridTextSize: 12,
        axes:false,
        padding:0,
        hideHover: 'auto',
        resize: true,
        pointSize: 0
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

    // Chart Donut
    var ctx1 = $('#chart-donut');
    new Chart(ctx1, {
        type: 'doughnut',
        data: {
            labels: ["BTC", "ETH", "ADA", "XRP"],
            datasets: [
                {
                    data: [1250, 970, 570, 290],
                    backgroundColor: [
                        "#F7931A",
                        "#282828",
                        "#3CC8C8",
                        "#f90a48"
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

    $('#sparkline1').sparkline('html', {
        type: 'bar',
        width:200,
        height:150,
        barWidth: 12,
        barSpacing: 3,
        barColor: '#f90a48',
        negBarColor: '#ccc',
        minSpotColor: '#f90a48',
        maxSpotColor: '#f90a48'
    });

});
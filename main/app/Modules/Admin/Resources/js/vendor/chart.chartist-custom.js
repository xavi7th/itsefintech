$(function () {
	// Line Chart 1
    new Chartist.Line('.ct-chart-1', {
        labels: ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        series: [
            [12, 9, 7, 8, 5],
            [2, 1, 3.5, 7, 3],
            [1, 3, 4, 5, 6]
        ]
    }, {
            fullWidth: true,
            chartPadding: {
                right: 40
            }
        });


	// Line Chart 2
    new Chartist.Line('.ct-chart-2', {
        labels: [1, 2, 3, 4, 5, 6, 7, 8],
        series: [
            [1, 2, 3, 1, -2, 0, 1, 0],
            [-2, -1, -2, -1, -3, -1, -2, -1],
            [0, 0, 0, 1, 2, 3, 2, 1],
            [3, 2, 1, 0.5, 1, 0, -1, -3]
        ]
    }, {
            high: 3,
            low: -3,
            fullWidth: true,
            axisY: {
                onlyInteger: true,
                offset: 20
            }
        });


	// Area Chart 1
    new Chartist.Line('.ct-chart-3', {
        labels: [1, 2, 3, 4, 5, 6, 7, 8, 9],
        series: [
            [5, 9, 7, 8, 5, 3, 5, 4, 6]
        ]
    }, {
            low: 0,
            showArea: true
        });


	// Area Chart 2
    new Chartist.Line('.ct-chart-4', {
        labels: [1, 2, 3, 4, 5, 6, 7, 8],
        series: [
            [1, 2, 3, 1, -2, 0, 1, 0],
            [-2, -1, -2, -1, -2.5, -1, -2, -1],
            [0, 0, 0, 1, 2, 2.5, 2, 1],
            [2.5, 2, 1, 0.5, 1, 0.5, -1, -2.5]
        ]
    }, {
            high: 3,
            low: -3,
            showArea: true,
            showLine: false,
            showPoint: false,
            fullWidth: true,
            axisX: {
                showLabel: false,
                showGrid: false
            }
        });


	// Bar Chart 1
    var data = {
        labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
        series: [
            [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
        ]
    };
    var data = {
        labels: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
        series: [
            [5, 4, 3, 7, 5, 10, 3, 4, 8, 10, 6, 8],
            [3, 2, 9, 5, 4, 6, 4, 6, 7, 8, 7, 4]
        ]
    };

    var options = {
        seriesBarDistance: 10
    };

    var responsiveOptions = [
        ['screen and (max-width: 640px)', {
            seriesBarDistance: 5,
            axisX: {
                labelInterpolationFnc: function (value) {
                    return value[0];
                }
            }
        }]
    ];

    new Chartist.Bar('.ct-chart-5', data, options, responsiveOptions);


	// Bar Chart 2
    var data = {
        labels: ['W1', 'W2', 'W3', 'W4', 'W5', 'W6', 'W7', 'W8', 'W9', 'W10'],
        series: [
            [1, 2, 4, 8, 6, -2, -1, -4, -6, -2]
        ]
    };

    var options = {
        high: 10,
        low: -10,
        axisX: {
            labelInterpolationFnc: function (value, index) {
                return index % 2 === 0 ? value : null;
            }
        }
    };

    new Chartist.Bar('.ct-chart-6', data, options);


	// Pie Chart 
    var data = {
        series: [5, 3, 4]
    };

    var sum = function (a, b) { return a + b };

    new Chartist.Pie('.ct-chart-7', data, {
        labelInterpolationFnc: function (value) {
            return Math.round(value / data.series.reduce(sum) * 100) + '%';
        }
    });


	// Donut Chart 
    new Chartist.Pie('.ct-chart-8', {
        series: [20, 10, 30, 40]
    }, {
            donut: true,
            donutWidth: 60,
            donutSolid: true,
            startAngle: 270,
            showLabel: true
        });

});

<script>
var fromSQLpath = 'http://www.altindex.io/from-sql.php';

var curStamp = $.ajax({
    url: "current.php",
    dataType: "text",
    async: false
}).responseText;

curStamp = curStamp.split(';');

var chart;

$(function () {

    /**
     * Load new data depending on the selected min and max
     */
    function afterSetExtremes(e) {
        chart = Highcharts.charts[0];

        chart.showLoading('Loading data from server...');

        $.getJSON(fromSQLpath + '?start=' + Math.round(e.min) +
                '&end=' + Math.round(e.max) + '&callback=?',
            function (data) {

                var ser1 = [];
                var ser2 = [];
                var ser3 = [];
                var ser4 = [];

                for(i = 0; i < data.length; i++){
                    ser1[i] = [data[i][0],data[i][1]];
                    ser2[i] = [data[i][0],data[i][2]];
                    ser3[i] = [data[i][0],data[i][3]];
                    ser4[i] = [data[i][0],data[i][4]];
                }
                
                chart.series[0].setData(ser1);
                chart.series[1].setData(ser2);
                chart.series[2].setData(ser3);
                chart.series[3].setData(ser4);
                chart.hideLoading();
            }
        );

        yAdjust();
    }

    function yAdjust() {
        yAxis = chart.yAxis[0],
        yExtremes = yAxis.getExtremes(),
        newMin = yExtremes.dataMin - 5,
        newMax = yExtremes.dataMax + 5;

        yAxis.setExtremes(newMin, newMax, true, false);
    }

    $.getJSON(fromSQLpath + '?callback=?', function (data) {
        // Add a null value for the end date - creates earlier start date
        //data = [].concat(data, [[Date.UTC(2011, 9, 14, 19, 59), null, null, null, null]]);
        var ser1 = [];
        var ser2 = [];
        var ser3 = [];
        var ser4 = [];

        for(i = 0; i < data.length; i++){
            ser1[i] = [data[i][0],data[i][1]];
            ser2[i] = [data[i][0],data[i][2]];
            ser3[i] = [data[i][0],data[i][3]];
            ser4[i] = [data[i][0],data[i][4]];
        }

        Highcharts.setOptions({
            global: {
                timezoneOffset: 5 * 60
            }
        });
        // create the chart
        chart = Highcharts.stockChart('container', {
            chart: {
                zoomType: 'x',
                renderTo: 'container',
                type: 'areaspline',
                fontFamily: 'serif',
                animation: false,
            },

            navigator: {
                enabled: false,
                adaptToUpdatedData: false,
                series: {
                    data: ser1
                }
            },

            legend:{
                enabled: true,
                align: 'center'
            },

            scrollbar: {
                enabled: true,
                liveRedraw: false
            },

            title: {
                text: 'T&C 20 - ' + curStamp[2],
                x: -20, //center
                useHTML: true
            },

            subtitle: {
                x: -20,
                text: 'last updated ' + curStamp[0] + ' at ' + curStamp[1],
            },

            rangeSelector: {
                buttons: [{
                    type: 'hour',
                    count: 1,
                    text: '1h'
                }, {
                    type: 'day',
                    count: 1,
                    text: '1d'
                }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                }, {
                    type: 'year',
                    count: 1,
                    text: '1y'
                }, {
                    type: 'all',
                    text: 'All'
                }],
                inputEnabled: false, // it supports only days
                selected: 4 // all
            },

            xAxis: {
                type: 'datetime',
                crosshair: {
                    width: 1,
                    color: '#cccccc'
                },
                ordinal: false,
                events: {
                    afterSetExtremes: afterSetExtremes
                },
                minRange: 3600 * 1000 // one hour
            },

            yAxis: {
                title: {
                    text: ""
                },
                crosshair: {
                    width: 1,
                    color: '#cccccc'
                }
            },

            series: [{
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.getOptions().colors[0]],
                        [1, Highcharts.Color(Highcharts.getOptions().colors[0]).setOpacity(0).get('rgba')]
                    ]
                },
                name: 'T&amp;C 20',
                data: ser1,
                dataGrouping: {
                    enabled: false
                }
            },
            {
                color: '#64cab2',
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.Color('#64cab2').setOpacity(1).get('rgba')],
                        [1, Highcharts.Color('#64cab2').setOpacity(0).get('rgba')]
                    ]
                },
                name: 'Icarus',
                data: ser2,
                dataGrouping: {
                    enabled: false
                }
            },
            {
                color: '#ff9900',
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.Color('#ff9900').setOpacity(1).get('rgba')],
                        [1, Highcharts.Color('#ff9900').setOpacity(0).get('rgba')]
                    ]
                },
                name: 'Bitcoin',
                data: ser3,
                dataGrouping: {
                    enabled: false
                }
            },
            {
                color: '#3c3c3d',
                fillColor: {
                    linearGradient: {
                        x1: 0,
                        y1: 0,
                        x2: 0,
                        y2: 1
                    },
                    stops: [
                        [0, Highcharts.Color('#3c3c3d').setOpacity(1).get('rgba')],
                        [1, Highcharts.Color('#3c3c3d').setOpacity(0).get('rgba')]
                    ]
                },
                name: 'Ethereum',
                data: ser4,
                dataGrouping: {
                    enabled: false
                }
            }],

            plotOptions: {
                areaspline: {
                    dataLabels: {
                        useHTML: true
                    }
                },
                series: {
                    connectNulls: true
                }
            },

            tooltip: {
                valueSuffix: '',
                valueDecimals: 2,
                useHTML: true
            },

            credits: {
                enabled: false,
            },

            rangeSelector: {
                enabled: true,
                allButtonsEnabled: true,
                selected: 4,
                buttons: [{
                    type: 'day',
                    count: 1,
                    text: '1d'
                }, {
                    type: 'day',
                    count: 7,
                    text: '7d'
                }, {
                    type: 'month',
                    count: 1,
                    text: '1m'
                }, {
                    type: 'month',
                    count: 3,
                    text: '3m'
                }, {
                    type: 'all',
                    text: 'All'
                }]
            },

            exporting: {
                enabled: true,
            }
        });

        <?php include('percent-change.php'); ?>

        adjustChart();

        function formatChange(num) {
            num = parseFloat(num);
            num = num.toFixed(2);
            var color = 'black';
            if(num<0){
                color = 'red';
            }else{
                color = 'green';
            }
            num = '<b style="font-size:12px;color:' + color + ';">' + num + '% (24h)</b>';
            return num;
        }

        //adjust chart when page is done loading
        function adjustChart() {
            chart.series[0].setVisible(true,false);
            chart.series[1].setVisible(false,false);
            chart.series[2].setVisible(false,false);
            chart.series[3].setVisible(false,false);
            chart.redraw()
            document.getElementById("tc").className = "btn btn-primary";
            document.getElementById("ica").className = "btn btn-default";
            document.getElementById("btc").className = "btn btn-default";
            document.getElementById("eth").className = "btn btn-default";
            var change = "<?php echo $tcchange ?>";             
            chart.setTitle({text: "T&C 20 <b>" + curStamp[2] + "</b> " + formatChange(change)}, {text:
                'updated ' + curStamp[0] + " at " + curStamp[1] + " EST"});

            yAdjust();
        }

        // the button action
        var $tc = $('#tc');
        $tc.click(function () {
            chart.series[0].setVisible(true,false);
            chart.series[1].setVisible(false,false);
            chart.series[2].setVisible(false,false);
            chart.series[3].setVisible(false,false);
            chart.redraw()
            document.getElementById("tc").className = "btn btn-primary";
            document.getElementById("ica").className = "btn btn-default";
            document.getElementById("btc").className = "btn btn-default";
            document.getElementById("eth").className = "btn btn-default";
            var latest = chart.yAxis[0].series[0].points;
            var change = "<?php echo $tcchange ?>"; 
            chart.setTitle({text: "T&C 20 <b>" + curStamp[2] + "</b> " + formatChange(change)});

            yAdjust();
        });

        var $ica = $('#ica');
        $ica.click(function () {
            chart.series[0].setVisible(false,false);
            chart.series[1].setVisible(true,false);
            chart.series[2].setVisible(false,false);
            chart.series[3].setVisible(false,false);
            chart.redraw()
            document.getElementById("tc").className = "btn btn-default";
            document.getElementById("ica").className = "btn btn-primary";
            document.getElementById("btc").className = "btn btn-default";
            document.getElementById("eth").className = "btn btn-default";
            var latest = chart.yAxis[0].series[1].points;
            var change = "<?php echo $icachange ?>"; 
            chart.setTitle({text: "Icarus Fund <b>$" + curStamp[3] + "</b> " + formatChange(change)}, {}, false);

            yAdjust();
        });

        var $btc = $('#btc');
        $btc.click(function () {
            chart.series[0].setVisible(false,false);
            chart.series[1].setVisible(false,false);
            chart.series[2].setVisible(true,false);
            chart.series[3].setVisible(false,false);
            chart.redraw()
            document.getElementById("tc").className = "btn btn-default";
            document.getElementById("ica").className = "btn btn-default";
            document.getElementById("btc").className = "btn btn-primary";
            document.getElementById("eth").className = "btn btn-default";
            var latest = chart.yAxis[0].series[2].points;
            var change = "<?php echo $btcchange ?>"; 
            chart.setTitle({text: "Bitcoin <b>$" + curStamp[4] + "</b> " + formatChange(change)}, {}, false);

            yAdjust();
        });

        var $eth = $('#eth');
        $eth.click(function () {
            chart.series[0].setVisible(false,false);
            chart.series[1].setVisible(false,false);
            chart.series[2].setVisible(false,false);
            chart.series[3].setVisible(true,false);
            chart.redraw()
            document.getElementById("tc").className = "btn btn-default";
            document.getElementById("ica").className = "btn btn-default";
            document.getElementById("btc").className = "btn btn-default";
            document.getElementById("eth").className = "btn btn-primary";
            var latest = chart.yAxis[0].series[3].points;
            var change = "<?php echo $ethchange ?>"; 
            chart.setTitle({text: "Ethereum <b>$" + curStamp[5] + "</b> " + formatChange(change)}, {}, false);

            yAdjust();
        });
    });
});
</script>

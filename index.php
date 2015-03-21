<?php include './globalVar.php'; ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $PageTitle; ?></title>
        <?php include './head.html'; include './dblogin.php'; ?>
        <?php
            $data = array();
            $query="select kanton.name,sum(spend*numTrx) from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id group by kanton.name";
            $result=mysql_query($query);

            while ($line=mysql_fetch_array($result)) {
                    $data[]=array("hc-key" => $line[0], "value" => $line[1]);
            }
        ?>
        <style type="text/css">
            ${demo.css}
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <script src="http://code.highcharts.com/maps/highmaps.js"></script>
        <script src="http://code.highcharts.com/maps/modules/exporting.js"></script>
        <script src="http://code.highcharts.com/mapdata/countries/ch/ch-all.js"></script>
        <script type="text/javascript">
            //<![CDATA[ 

            $(function () {

            // Initiate the chart
            $('#container').highcharts('Map', {

            chart: {
                height: 400,
            },

            title: {
                text: 'Where Lucern tourists spend their money?'
            },

            mapNavigation: {
                enabled: true,
                buttonOptions: {
                    verticalAlign: 'bottom'
                }
            },

            colorAxis: {
                min: 0
            },

            series: [{
                data: <?php echo json_encode($data, JSON_NUMERIC_CHECK)?>,
                mapData: Highcharts.maps['countries/ch/ch-all'],
                joinBy: 'hc-key',
                name: 'total spending',
                states: {
                    hover: {
                        color: '#BADA55'
                    }
                },
                dataLabels: {
                    enabled: true,
                    format: '{point.name}'
                }
            }]
            });
            });
            //]]>
        </script>
    </head>
    <body>
        <div class="container">
            <div class="headerbar">
                <div class="headerimage fltleft"><img src="Logo_1.png" alt="LOGO" height="120" /></div>
                <div class="headerextension">
                    <div class="brown"></div>
                    <div class="blue"></div>
                </div>
                <div class="clear"></div>
            </div>
            <div class="navigation">
                <ul>
                    <center>
                        <li class="fltleft navitem"><a href="index.php">Übersicht</a></li>
                        <li class="fltleft navitem"><a href="days.php">Tage</a></li>
                    </center>
                </ul>
            </div>
            <div class="content">
                <div id="container"></div>
            </div>
        </div>
    </body>
</html>

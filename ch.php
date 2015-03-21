<!DOCTYPE HTML>
<html>
	<?php
		include './dblogin.php';
		$data = array();
		$query="select kanton.name,sum(spend*numTrx) from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id group by kanton.name";
		$result=mysql_query($query);
                
		while ($line=mysql_fetch_array($result)) {
			$data[]=array("hc-key" => $line[0], "value" => $line[1]);
		}
	?>
	<head>
		<meta http-equiv="Content-Type" content="text/html">
                <meta charset="UTF-8">
		<title>Highmaps Example</title>

		<script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
//<![CDATA[ 

$(function () {

    // Initiate the chart
    $('#container').highcharts('Map', {

    	chart: {
            height: 600,
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
  <script src="http://code.highcharts.com/maps/highmaps.js"></script>
<script src="http://code.highcharts.com/maps/modules/exporting.js"></script>
<script src="http://code.highcharts.com/mapdata/countries/ch/ch-all.js"></script>
<div id="container"></div>
  
</body>


</html>

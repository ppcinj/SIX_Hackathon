<!DOCTYPE html>
<html>
	<?php
		include './dblogin.php';
		$cat= array();	
		$kan= array();
		$data = array();
		
		$query2="select distinct days from import order by days desc";
		$result2=mysql_query($query2);
		while ($line=mysql_fetch_array($result2)) {
			$cat[]=$line[0];
		}
		
		$query3="select kanton.name,kanton.fullname,sum(spend*numTrx) as total from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id group by import.days,kanton.name having total > 50";
		$result3=mysql_query($query3);
		while ($line=mysql_fetch_array($result3)) {
			$data[$line[0]]=array("name" => $line[1], "data" => array_fill(0,count($cat),0));
		}
		
		$query4="select import.days,sum(spend*numTrx) as total from import group by import.days order by days desc";
		$result4=mysql_query($query4);
		while ($line=mysql_fetch_array($result4)) {
			$turnover[]=$line[1];
		}
				
		$query="select import.days,kanton.name,sum(spend*numTrx) as total from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id group by import.days,kanton.name having total > 50";
		$result=mysql_query($query);                
		while ($line=mysql_fetch_array($result)) {
			$data[$line[1]]["data"][10-$line[0]]=$line[2];
		}		
		//print_r(get_defined_vars());
		$data=array_values($data);
		array_push($data,array("type" => "column", "name" => "turnover", "data" => $turnover, "yAxis" => 1));
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
    $('#container').highcharts({
        chart: {
            type: 'areaspline'
        },
        title: {
            text: 'days and after Lucern visit'
        },
        legend: {
            layout: 'vertical',
            align: 'left',
            verticalAlign: 'top',
            x: 150,
            y: 100,
            floating: true,
            borderWidth: 1,
            backgroundColor: (Highcharts.theme && Highcharts.theme.legendBackgroundColor) || '#FFFFFF'
        },
        xAxis: {
            categories: <?php echo json_encode($cat, JSON_NUMERIC_CHECK)?>,
            		title: {
                        text: 'days before'
                    }
        },
        yAxis: [{
            height: 150,
            title: {
                text: 'total spending'
            },
        }, {
            title: {
                text: 'total spenidng'
            },
            top: 220,
            height: 100,
            offset: 0,
            lineWidth: 2
        }],
        tooltip: {
            shared: true,
            valueSuffix: ' CHF'
        },
        credits: {
            enabled: false
        },
        plotOptions: {
            areaspline: {
                fillOpacity: 0.5
            }
        },
        series: <?php echo json_encode($data, JSON_NUMERIC_CHECK)?>,
    });
});
//]]>  

</script>


</head>
<body>
  <script src="http://code.highcharts.com/highcharts.js"></script>
<script src="http://code.highcharts.com/modules/exporting.js"></script>

<div id="container" style="min-width: 310px; height: 400px; margin: 0 auto"></div>

  
</body>


</html>



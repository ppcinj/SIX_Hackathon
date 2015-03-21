<!DOCTYPE html>
<html>
    <?php
        include './dblogin.php';
        include './globalVar.php';
        $selectedCountry=$_GET['selectedCountry'];
        if ($selectedCountry == -1 | !isset($_GET['selectedCountry'])) { $selectedCountry="1,2,3,4,5,6,7,8,9,10,11,12,13,14"; }		
        $cat= array();	
        $kan= array();
        $data = array();

        $query_cat="select distinct days from import order by days desc";
        $result2=mysql_query($query_cat);
        while ($line=mysql_fetch_array($result2)) {
                $cat[]=$line[0];
        }

        $query4="select import.days,sum(spend*numTrx) as total from import where country in (".$selectedCountry.") group by import.days order by days desc";
        $result4=mysql_query($query4);
        while ($line=mysql_fetch_array($result4)) {
                $turnover[]=$line[1];
        }
        $turnover_sum=array_sum($turnover);

        $query6="select merchCategory,name,sum(spend*numTrx) as total from import,merch where import.merchCategory=merch.id and country in (".$selectedCountry.") group by days,merchCategory having total > ".$turnover_sum*0.007."";
        $result6=mysql_query($query6);
        while ($line=mysql_fetch_array($result6)) {
                $column[$line[0]]=array("type" => "column","name" => $line[1], "data" => array_fill(0,count($cat),0), "yAxis" => 1, "visible" => false);
        }

        $query5="select import.days,merchCategory,sum(spend*numTrx) as total from import where country in (".$selectedCountry.") group by days,merchCategory having total > ".$turnover_sum*0.007." order by days desc";
        $result5=mysql_query($query5);
        while ($line=mysql_fetch_array($result5)) {
                $column[$line[1]]["data"][10-$line[0]]=$line[2];
        }

        $query3="select kanton.name,kanton.fullname,sum(spend*numTrx) as total from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id and country in (".$selectedCountry.") group by import.days,kanton.name having total  > ".$turnover_sum*0.005."";
        $result3=mysql_query($query3);
        while ($line=mysql_fetch_array($result3)) {
                $data[$line[0]]=array("name" => $line[1], "data" => array_fill(0,count($cat),0));
        }

        $query="select import.days,kanton.name,sum(spend*numTrx) as total from import,locations,kanton where import.location=locations.id and locations.kt=kanton.id and country in (".$selectedCountry.") group by import.days,kanton.name having total  > ".$turnover_sum*0.005."";
        $result=mysql_query($query);                
        while ($line=mysql_fetch_array($result)) {
                $data[$line[1]]["data"][10-$line[0]]=$line[2];
        }		

        $column=array_values($column);
        $data=array_values($data);
        $data=array_merge($data,$column);
    ?>
    <head>
        <meta charset="UTF-8">
        <title><?php echo $PageTitle; ?></title>
        <?php include './head.html'; include './dblogin.php'; ?>
        <style type="text/css">
            <?php echo "${demo.css}"; ?>
        </style>
        <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.8.2/jquery.min.js"></script>
        <!--<script src="http://code.highcharts.com/maps/highmaps.js"></script>
        <script src="http://code.highcharts.com/maps/modules/exporting.js"></script>
        <script src="http://code.highcharts.com/mapdata/countries/ch/ch-all.js"></script>-->
        <script src="http://code.highcharts.com/highcharts.js"></script>
        <script src="http://code.highcharts.com/modules/exporting.js"></script>
        <script type="text/javascript">
        //<![CDATA[ 

        $(function () {
            $('#container').highcharts({
                chart: {
                    type: 'areaspline',
                    marginRight: 180
                },
                title: {
                    text: 'What Lucern tourists do before they come to Lucern?'
                },
                legend: {
                    layout: 'vertical',
                    align: 'right',
                    verticalAlign: 'top',
                    y:100,
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
                    height: 250,
                    title: {
                        text: 'total spending / area'
                    },
                }, {
                    title: {
                        text: 'total spenidng / sector'
                    },
                    top: 320,
                    height: 130,
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
                    },
                    column: {
                        stacking: 'normal'
                    },
                },
                series: <?php echo json_encode($data, JSON_NUMERIC_CHECK)?>,
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
                        <li class="fltleft navitem"><a href="index.php">Overview</a></li>
                        <li class="fltleft navitem"><a href="days.php">Daily Spend</a></li>
                    </center>
                </ul>
            </div>
            <div class="content">
                <form method="get" id="frmSelectCountry">
                    <span style="margin-left: 40px;">Customer Origin Country: </span><select id="soflow" onchange="document.getElementById('frmSelectCountry').submit();" name='selectedCountry'>
                    <option value="-1">All</option>
                    <?php
                        $res = mysql_query("select * from country");
                        while ($row = mysql_fetch_assoc($res))
                        {
                            echo "<option value='".$row["id"]."' ";
                            if ($_GET["selectedCountry"] == $row["id"])
                                echo "selected";
                            echo ">".$row["name"]."</option>";
                        }
                    ?>
                    </select>
                </form>
                <div id="container" style="min-width: 300px; height: 505px; margin: 0 auto"></div>
            </div>
        </div>
    </body>
</html>



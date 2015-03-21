<!DOCTYPE html>
<html>
    <?php
        include './dblogin.php';
        include './globalVar.php';
        $selectedCountry=$_GET['selectedCountry'];
        
        $useContinentSelection = false;
        
        if (substr($_GET["selectedCountry"], 0, 1) == "#")
        {
            $useContinentSelection = true;
            $selectedCountry = substr($_GET["selectedCountry"], 1, 1);
            $selectedCountry = "select id from country where continent =".$selectedCountry;
        }
        
        if ($selectedCountry == -1 | !isset($_GET['selectedCountry'])) { $selectedCountry="1,2,3,4,5,6,7,8,9,10,11,12,13,14"; }		
        $cat= array();	
        $data = array();

        $query_cat="select distinct name from merch order by id";
        $result2=mysql_query($query_cat);
        while ($line=mysql_fetch_array($result2)) {
        	$cat[]=$line[0];
        }
        
        $data["before"]=array("name" => "before", "data" => array_fill(0,count($cat),0));
        $data["after"]=array("name" => "after", "data" => array_fill(0,count($cat),0));
            
        $query1="select merchCategory,if(days>0,'before','after') as cat,sum(spend*numTrx) from import where days!=0 and country in (".$selectedCountry.") group by merchCategory,cat";
        $result1=mysql_query($query1);
        while ($line=mysql_fetch_array($result1)) {
                 if ($line[1] == "before") { $value=$line[2]*-1; } else { $value=$line[2];}
                 $data[$line[1]]["data"][$line[0]-1]=$value;
        }
        $data=array_values($data);
        
        //print_r(get_defined_vars());
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
        <script type="text/javascript">//<![CDATA[ 

$(function () {
    $(document).ready(function () {
        $('#container').highcharts({
            chart: {
                type: 'bar'
            },
            title: {
                text: 'test'
            },
            xAxis: [{
                categories: <?php echo json_encode($cat, JSON_NUMERIC_CHECK)?>,
                reversed: false,
                labels: {
                    step: 1
                }
            }, { // mirror axis on right side
                opposite: true,
                reversed: false,
                categories: <?php echo json_encode($cat, JSON_NUMERIC_CHECK)?>,
                linkedTo: 0,
                labels: {
                    step: 1
                }
            }],
            yAxis: {
                title: {
                    text: null
                },
                labels: {
                    formatter: function () {
                        return (Math.abs(this.value));
                    }
                },
            },

            tooltip: {
                formatter: function () {
                    return '<b>' + this.series.name + '</b><br/>' +
                        'Total spending: ' + Highcharts.numberFormat(Math.abs(this.point.y), 0) + ' CHF';
                }
            },

            plotOptions: {
                series: {
                    stacking: 'normal'
                }
            },

            series: <?php echo json_encode($data, JSON_NUMERIC_CHECK)?>,
            
        });
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
                    <?php include './navitems.php'; ?>
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
                        echo "<<option disabled>-----------</option>";
                        $res = mysql_query("select * from continent");
                        while ($row = mysql_fetch_assoc($res))
                        {
                            echo "<option value='#".$row["id"]."' ";
                            if ($_GET["selectedCountry"] == "#".$row["id"])
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



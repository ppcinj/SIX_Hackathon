<?php
    include './dblogin.php';
    $countries = mysql_query("select * from country");
    
    while ($rowcountry = mysql_fetch_assoc($countries))
    {
        $updatequery = "update import set country = '".$rowcountry["id"]."' where import.country = '".$rowcountry["name"]."'";
        mysql_query($updatequery);
        echo "Affected rows: ".mysql_affected_rows().", query: ".$updatequery."<br>";
    }
    
    $merchs = mysql_query("select * from merch");
    
    while ($rowmerch = mysql_fetch_assoc($merchs))
    {
        $updatequery = "update import set merchCategory = '".$rowmerch["id"]."' where import.merchCategory = '".$rowmerch["name"]."'";
        mysql_query($updatequery);
        echo "Affected rows: ".mysql_affected_rows().", query: ".$updatequery."<br>";
    }
    
    $location = mysql_query("select * from locations");
    
    while ($rowlocation = mysql_fetch_assoc($location))
    {
        $updatequery = "update import set location = '".$rowlocation["id"]."' where import.location = '".$rowlocation["name"]."'";
        mysql_query($updatequery);
        echo "Affected rows: ".mysql_affected_rows().", query: ".$updatequery."<br>";
    }
    
    $cantons = mysql_query("select * from kanton");
    
    while ($rowcanton = mysql_fetch_assoc($cantons))
    {
        $updatequery = "update locations set kt = '".$rowcanton["id"]."' where locations.kt = '".$rowcanton["name"]."'";
        mysql_query($updatequery);
        echo "Affected rows: ".mysql_affected_rows().", query: ".$updatequery."<br>";
    }
?>


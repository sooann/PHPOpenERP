<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

include_once('openerpdata.php');

$data = new OpenERPdata;

//testing querying valid product code
$productcode = "2646406472218";
echo ("Testing valid Product code: $productcode<br />");
$res = $data->queryProduct($productcode);
echo ("ID: ".$res[0]["id"]."<br />");
echo ("Code: ".$res[0]["code"]."<br />");
echo ("Name: ".$res[0]["name"]."<br />");
echo ("EAN13 Code: ".$res[0]["ean13"]."<br />");

//testing querying price for valid product code
$price = $data->queryProductPrice("1", $res[0]["id"], 1);
echo ("Price: ".$price."<br />");
echo ("<br />");

//testing querying valid product code
$productcode = "00000";
echo ("Testing invalid Product code: $productcode<br />");
$res = $data->queryProduct($productcode);
print_r($res);

?>

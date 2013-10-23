<?php

    include_once('openerp.class.php');
    
    $pricelist = "2";
    $date = "2013-11-23";
    $qty = 1;
    $partner = 0;

    $rpc = new OpenERP();
    $rpc->login("admin", "admin", "AuraDB6", "http://localhost:8069/xmlrpc/");

    $products = $rpc->search("product.product", null);
    
    foreach ($products as $product) {
        
        $product_id = array($product);
        $res = $rpc->read($product_id, array("name","id","ean13","code"), "product.product");
        echo ("get price for ".$res[0]["name"].":<br />");
        echo ("ID: ".$res[0]["id"]."<br />");
        echo ("Code: ".$res[0]["code"]."<br />");
        echo ("Name: ".$res[0]["name"]."<br />");
        echo ("EAN13 Code: ".$res[0]["ean13"]."<br />");
        
        
        $vals = array(
            'id'=>array(array(
                new xmlrpcval($pricelist,"string")
                ),"array"),
            'prod_id'=>array($res[0]["id"],"int"),
            'qty'=>array($qty,"int"),
            'partner'=>array($partner,"int"),
            'context'=>array(array(
                'date'=>new xmlrpcval($date,"string")
                ),"struct")
            );

        $res = $rpc->call_function("product.pricelist", "price_get", $vals);
        $price = $res->structmem($pricelist)->scalarval();
        echo ("Price: ".$price."<br />");
        
        echo "<br />";
    }
?>

<?php

    include_once('openerp.class.php');

    $rpc = new OpenERP();

    $rpc->login("admin", "admin", "AuraDB6", "http://localhost:8069/xmlrpc/");
    
    $res = $rpc->search("product.pricelist", null);
    print_r($res);
    echo "<br />";
    
    $res = $rpc->read($res, null, "product.pricelist");
    print_r($res);
    echo "<br />";
    
    $res = $rpc->call_function("product.pricelist", "price_get", $ids, $params)
?>

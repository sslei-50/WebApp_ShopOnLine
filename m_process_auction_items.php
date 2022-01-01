<?php

header('Content-Type: text/xml');

$HTML = "";

$r_dom = DOMDocument::load("auction.xml");
$items = $r_dom->getElementsByTagName("ListedItem"); 

$cdate = date("Y-m-d");           // get the current date
$ctime = date("H:i:s");           // get the current time
$currTimeStr = $cdate." ".$ctime;


foreach($items as $r_item) 
{ 
    
    $r_statusNode = $r_item->getElementsByTagName("Status");
    $r_statusNodeValue = $r_statusNode->item(0)->nodeValue;

    $r_durationNode = $r_item->getElementsByTagName("Duration");
    $r_durationNodeValue = $r_durationNode->item(0)->nodeValue;

    $r_reservepriceNode = $r_item->getElementsByTagName("ReservePrice");
    $r_reservepriceNodeValue = $r_reservepriceNode->item(0)->nodeValue;

    $r_bidpriceNode = $r_item->getElementsByTagName("BidPrice");
    $r_bidpriceNodeValue = $r_bidpriceNode->item(0)->nodeValue;

    if($r_statusNodeValue == "in progress") {
        //check if the item expires
        $durTimeStr = $r_durationNodeValue;
        $currTime = strtotime($currTimeStr);
        $durTime = strtotime($durTimeStr);
        $diff = $durTime - $currTime;
        
        //decide status if $diff is zero or negative - meaning pass the duration time
        if ($diff <= 0) {
            if ($r_bidpriceNodeValue >= $r_reservepriceNodeValue) {
                $r_statusNode->item(0)->nodeValue = "sold";     //update status to 'sold' if bid price >= reserve price
            } else {
                $r_statusNode->item(0)->nodeValue = "failed";     //update status to 'failed' if not
            }
        }
    }
}

$savedcorrectly= $r_dom->save("auction.xml");
$HTML ="<br><span class='confirmed'>Process is complete.<span/><br/>";
echo $HTML;

?>
<?php
	/*
    */

function getUniqueID($fileName, $itemNodeName, $itemIDNodeName)
    {    
        $xdoc = new DomDocument("1.0");     //create a new DomDocument Object
        $xdoc->Load($fileName);       //Load your pre-existing customers.xml        
        //get the root node of the document, e.g. the "Customers" tag.
        $ItemsNode = $xdoc->documentElement;
        $items = $xdoc->getElementsByTagName($itemNodeName);      //e.g. the "Customer" tag

        //create a unique ID, e.g. customerID, itemID
        $idArray = [];
        foreach($items as $node) {
            $itemIDNode = $node->getElementsByTagName($itemIDNodeName);
            $itemID = $itemIDNode->item(0)->nodeValue;
            $itemID = (int)$itemID;
            array_push($idArray, $itemID);
        }
        rsort($idArray);
        $result = ($idArray[0]) + 1;

        return $result;
    }

    function isDurationExpired($durTimeStr) {
        $cdate = date("Y-m-d");           // get the current date
        $ctime = date("H:i:s");           // get the current time
        $currTimeStr = $cdate." ".$ctime;
        //check if the item expires
        $currTime = strtotime($currTimeStr);
        $durTime = strtotime($durTimeStr);
        $diff = $durTime - $currTime;
        if ($diff > 0){
            return false;
        } else {
            return true;
        }

    }

            
?>
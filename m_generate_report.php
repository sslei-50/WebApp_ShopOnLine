<?php
	/*
    */
    
    header('Content-Type: text/xml');

    $xmlDoc = new DomDocument; 
    $xmlDoc->load("auction.xml"); 
    $xslDoc = new DomDocument; 
    $xslDoc->load("auction.xsl"); 
    $proc = new XSLTProcessor; 
    $proc->importStyleSheet($xslDoc); 
    echo $proc->transformToXML($xmlDoc);
    removeSoldandFailed();


    function removeSoldandFailed() {

        $r_dom = DOMDocument::load("auction.xml");
        $itemsList = $r_dom->getElementsByTagName("ListedItem");    //this gives a list of the items

        //https://stackoverflow.com/questions/1153697/php-delete-xml-element

        
        //figure out which ones you want -- assign it to a variable (ie: $nodeToRemove )
        $nodesToRemove = [];
        foreach ($itemsList as $node){
            $r_statusNode = $node->getElementsByTagName("Status");
            $r_statusNodeValue = $r_statusNode->item(0)->nodeValue;
            if (($r_statusNodeValue == 'sold') || ($r_statusNodeValue == 'failed')) {
                array_push($nodesToRemove, $node);
            }
        }

        //Now remove it.
        foreach ($nodesToRemove as $node) {
            $node->parentNode->removeChild($node);
        }

       $savedcorrectly= $r_dom->save("auction.xml");
    }


   
?>
  

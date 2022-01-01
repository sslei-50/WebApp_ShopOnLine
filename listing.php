<?php
	/*check session data for seller ID
    */
    session_start();
    header('Content-Type: text/xml');
    $HTML = "";

    if(!isset($_SESSION["custID"])){
        $HTML ="<br><span class='failed'>Please log in first to list your item for auction.<span/><br/>";
    } else {
        $custID = $_SESSION["custID"]; 
    
        if ((isset($_POST["itemname"])) && 
            (isset($_POST["category"])) &&
            (isset($_POST["desc"])) &&
            (isset($_POST["startprice"])) &&
            (isset($_POST["reserveprice"])) &&
            (isset($_POST["buynowprice"])) &&
            (isset($_POST["day"])) &&
            (isset($_POST["hour"])) &&
            (isset($_POST["min"]))) 
        {
            $iname = trim($_POST["itemname"]);    //assign the values from the POST action into the variable
            $cate = trim($_POST["category"]); 
            $des = trim($_POST["desc"]);
            $startp = trim($_POST["startprice"]); 
            $reservep = trim($_POST["reserveprice"]); 
            $buynowp = trim($_POST["buynowprice"]); 
            $d = trim($_POST["day"]);
            $h = trim($_POST["hour"]);  
            $m = trim($_POST["min"]); 

            $dur = constructDuration($d, $h, $m);
            $cdate = date("Y-m-d");           // get the current date
            $ctime = date("H:i:s");           // get the current time

            require_once "utility.php";	
            $id = getUniqueID("auction.xml", "ListedItem", "ItemID");

            toXml($iname, $cate, $des, $startp, $reservep, $buynowp, $dur, $cdate, $ctime, $id, $custID);

            $HTML ="<br><span class='confirmed'>Successfully listed. Your item has been listed in ShopOnline. 
                            The item number is ".$id." and the bidding starts now: ".$ctime." on ".$cdate.".</span></br>";
        }      
        else 
        {
            $HTML ="<br><span class='failed'>Something wrong with the listing.</span></br>";
        }
    }

    echo $HTML;

    

    //construct duration
    function constructDuration($d, $h, $m) {
        $durationToAdd = "P".$d."DT".$h."H".$m."M";
        $duration = new DateTime(date('y-m-d H:i:s'));
        $duration->add(new DateInterval($durationToAdd));

        $duration = $duration->format("Y-m-d H:i:s");    //date->format to return a string object
        return $duration;
    }

    function toXml($iname, $cate, $des, $startp, $reservep, $buynowp, $dur, $cdate, $ctime, $id, $custID)
    {
        
        //https://www.toolbox.com/tech/programming/question/add-another-group-of-elements-to-the-existing-xml-file-111509/
        
        $xdoc = new DomDocument("1.0");     //create a new DomDocument Object
        $xdoc->preserveWhiteSpace = false;   //need this for formatOutput to work - https://www.php.net/manual/en/domdocument.savexml.php
        $xdoc->Load("auction.xml");       //Load your pre-existing customers.xml
        $xdoc->formatOutput = true;         //output a nice format of XML
        //get the root node of the document which is the Customers tag.
        $ListedItemsNode = $xdoc->documentElement;

        //create a item node for the new item and append it
        $listeditemNodeElement=$xdoc->createElement("ListedItem");
        $listeditemNode=$ListedItemsNode->appendChild($listeditemNodeElement);

        //create each tag to go within the item node and then append it to the item node
        //1. the itemID tag

        //create a itemID element tag
        $itemID = $xdoc->createElement("ItemID");
        //append the itemname tag to our customer node
        $itemIDNode=$listeditemNode->appendChild($itemID);
    
        //now create and append the text node within the itemname tag

        //create a itemname text node element using data from our form
        $itemIDtextnode=$xdoc->createTextNode($id);
        //append the itemname data text node to the itemname node
        $itemIDNode->appendChild($itemIDtextnode);
            
        //2. the ItemName tag
        $itemname = $xdoc->createElement("ItemName");
        $itemnameNode=$listeditemNode->appendChild($itemname);
        $itemnametextnode=$xdoc->createTextNode($iname);
        $itemnameNode->appendChild($itemnametextnode);

        //3. the Category tag
        $category = $xdoc->createElement("Category");
        $categoryNode=$listeditemNode->appendChild($category);
        $categorytextnode=$xdoc->createTextNode($cate);
        $categoryNode->appendChild($categorytextnode);
    
        //4. the Description tag
        $desc = $xdoc->createElement("Description");
        $descNode=$listeditemNode->appendChild($desc);
        $desctextnode=$xdoc->createTextNode($des);
        $descNode->appendChild($desctextnode);

        //5. the StartPrice tag
        $startprice = $xdoc->createElement("StartPrice");
        $startpriceNode=$listeditemNode->appendChild($startprice);
        $startpricetextnode=$xdoc->createTextNode($startp);
        $startpriceNode->appendChild($startpricetextnode);

        //6. the ReservePrice tag
        $reserveprice = $xdoc->createElement("ReservePrice");
        $reservepriceNode=$listeditemNode->appendChild($reserveprice);
        $reservepricetextnode=$xdoc->createTextNode($reservep);
        $reservepriceNode->appendChild($reservepricetextnode);

        //7. the BuyItNowPrice tag
        $buynowprice = $xdoc->createElement("BuyItNowPrice");
        $buynowpriceNode=$listeditemNode->appendChild($buynowprice);
        $buynowpricetextnode=$xdoc->createTextNode($buynowp);
        $buynowpriceNode->appendChild($buynowpricetextnode);

        //8. the Duration tag
        $duration = $xdoc->createElement("Duration");
        $durationNode=$listeditemNode->appendChild($duration);
        $durationtextnode=$xdoc->createTextNode($dur);
        $durationNode->appendChild($durationtextnode);

        //9. the CurrentDate tag
        $currdate = $xdoc->createElement("CurrentDate");
        $currdateNode=$listeditemNode->appendChild($currdate);
        $currdatetextnode=$xdoc->createTextNode($cdate);
        $currdateNode->appendChild($currdatetextnode);

        //10. the CurrentTime tag
        $currtime = $xdoc->createElement("CurrentTime");
        $currtimeNode=$listeditemNode->appendChild($currtime);
        $currtimetextnode=$xdoc->createTextNode($ctime);
        $currtimeNode->appendChild($currtimetextnode);

        //11. the SellerID tag
        $sellerID = $xdoc->createElement("SellerID");
        $sellerIDNode=$listeditemNode->appendChild($sellerID);
        $sellerIDtextnode=$xdoc->createTextNode($custID);
        $sellerIDNode->appendChild($sellerIDtextnode);

        //12. the Status tag
        $status = $xdoc->createElement("Status");
        $statusNode=$listeditemNode->appendChild($status);
        $statustextnode=$xdoc->createTextNode("in progress");    //"in progress" by default. Other values: "sold", or "failed"
        $statusNode->appendChild($statustextnode);

        //13. the BidderID tag
        $bidderID = $xdoc->createElement("BidderID");
        $bidderIDNode=$listeditemNode->appendChild($bidderID);
        $bidderIDtextnode=$xdoc->createTextNode("");                //"" empty upon created
        $bidderIDNode->appendChild($bidderIDtextnode);

        //14. the BidPrice tag
        $bidprice = $xdoc->createElement("BidPrice");
        $bidpriceNode=$listeditemNode->appendChild($bidprice);
        $bidpricetextnode=$xdoc->createTextNode("");             //"" empty upon created
        $bidpriceNode->appendChild($bidpricetextnode);

        //Dump the internal XML tree back into the file
        $savedcorrectly= $xdoc->save("auction.xml");

    }

   
        
?>
/**
 * Author: Sam Sam Lei (103196737) 
 * Target: JavaScript used with 'm_process_auction_items.php' and 'm_generate_report.php'
 * Purpose: COS80021 Assignment 2  
 */

 "use strict";    //to indicate that the code should be executed in "strict mode" where no undeclared variables can be used.

 var xHRObject = false;

 if (window.XMLHttpRequest)
     xHRObject = new XMLHttpRequest();
 else if (window.ActiveXObject)
     xHRObject = new ActiveXObject("Microsoft.XMLHTTP");



function generateReport() 
{     
        //report will be translated from XML to XSL
        xHRObject.open("POST", "m_generate_report.php", true);
        xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xHRObject.onreadystatechange = getData;
        xHRObject.send();
}


function getData()
{
    //report will be translated from XML to XSL
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
		var serverResponse = xHRObject.responseText;               
        var spantag = document.getElementById("report");
        spantag.innerHTML = serverResponse;
    }
}



function processAuctionItems(){
    xHRObject.open("POST", "m_process_auction_items.php", true);
        xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xHRObject.onreadystatechange = getConfirmation;
        xHRObject.send();
}


function getConfirmation() {
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
        document.getElementById('processAuctionItemsConfirmation').innerHTML = xHRObject.responseText;
    }
}





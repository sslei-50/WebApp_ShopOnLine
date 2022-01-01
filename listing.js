/**
 * Author: Sam Sam Lei (103196737) 
 * Target: JavaScript used with 'listing.php'
 * Purpose: COS80021 Assignment 2  
 */

 "use strict";    //to indicate that the code should be executed in "strict mode" where no undeclared variables can be used.

var xHRObject = false;

if (window.XMLHttpRequest)
    xHRObject = new XMLHttpRequest();
else if (window.ActiveXObject)
    xHRObject = new ActiveXObject("Microsoft.XMLHTTP");


function getResults() 
{
    var argument = "value=";
    var itemname = document.getElementById("itemName").value; 
    var c = document.getElementById("categorySelected"); 
    var category = c.options[c.selectedIndex].value;
    var desc = document.getElementById("description").value; 
    var startprice = document.getElementById("startPrice").value; 
    var reserveprice = document.getElementById("reservePrice").value; 
    var buynowprice = document.getElementById("buyNowPrice").value; 
    var d = document.getElementById("day"); 
    var day = d.options[d.selectedIndex].value;
    var h = document.getElementById("hour"); 
    var hour = h.options[h.selectedIndex].value;
    var m = document.getElementById("minute"); 
    var min = m.options[m.selectedIndex].value;   
    
    //validation
    var val1 = is_number(day, hour, min, startprice, reserveprice, buynowprice);
    var val2 = is_input(itemname, desc);
    var val3 = validate_reserveprice(startprice, reserveprice);
    var val4 = validate_buynowprice(buynowprice, reserveprice);

    if(val1 && val2 && val3 && val4)  
    {
        var argument = "value=";
        argument = argument 
                + "&itemname="+itemname 
                + "&category="+category 
                + "&desc="+desc 
                + "&startprice="+startprice
                + "&reserveprice="+reserveprice
                + "&buynowprice="+buynowprice
                + "&day="+day
                + "&hour="+hour
                + "&min="+min; 
        
        xHRObject.open("POST", "listing.php", true);
        xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xHRObject.onreadystatechange = getData;
        xHRObject.send(argument);
    }
}



function getData() 
{
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
		//     alert(xHRObject.responseXML);
		//    alert(xHRObject.responseText);
        var serverResponse = xHRObject.responseText;                     //not getting the response in XML?????
        
        var spantag = document.getElementById("listingResult");
        spantag.innerHTML = serverResponse;
    }
}

function is_input(itemname, desc){
    if (itemname == "") {
        alert("Must enter an Item Name");
        return false;
    }
    if (desc == "") {
        alert("Must enter a Description for your item");
        return false;
    }
    return true;
}

function is_number(d, h, m, sprice, rprice, bprice){
    if (isNaN(d)) {
        alert("Must choose a value in 'Day' for Duration");
        return false;
    }
    if (isNaN(h)) {
        alert("Must choose a value in 'Hour' for Duration");
        return false;
    }
    if (isNaN(m)) {
        alert("Must choose a value in 'Min' for Duration");
        return false;
    }
    if (sprice == "") { 
        alert("Must enter a Start Price");
        return false;
    }
    if (rprice == "") { 
        alert("Must enter a Reserve Price");
        return false;
    }
    if (bprice == "") { 
        alert("Must choose a Buy It Now Price");
        return false;
    }
    return true;
}

function setDecimal(input) {
    input.value = parseFloat(input.value).toFixed(2);
}


// function validate_startprice(startprice, reserveprice){
//     startprice = startprice.trim();
//     startprice = parseFloat(startprice);
//     reserveprice = reserveprice.trim();
//     reserveprice = parseFloat(reserveprice);
//     if (startprice < 0) {
//         alert("'Start price' must be equal or higher that 0");
//         return false;        
//     }
//     if (startprice > reserveprice) {
//         alert("'Start price' must be no more than the 'Reserve price'");
//         return false;        
//     }
//     return true;
// }

function validate_reserveprice(startprice, reserveprice){
    startprice = startprice.trim();
    startprice = parseFloat(startprice);
    reserveprice = reserveprice.trim();
    reserveprice = parseFloat(reserveprice);
    if ((reserveprice <= 0) || (reserveprice < startprice)) {
        alert("'Reserve Price' must be higher than the 'Start Price'");
        return false;        
    }
    return true;
}

function validate_buynowprice(buynowprice, reserveprice){
    buynowprice = buynowprice.trim();
    buynowprice = parseFloat(buynowprice);
    reserveprice = reserveprice.trim();
    reserveprice = parseFloat(reserveprice);
    //alert ( buynowprice);
   // alert (reserveprice);
    if ((buynowprice <= 0) || (buynowprice < reserveprice)) {
        alert("'Buy It Now Price' must be higher than the 'Reserve Price'");
        return false;        
    }
    return true;
}






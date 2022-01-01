/**
 * Author: Sam Sam Lei (103196737) 
 * Target: JavaScript used with 'b_placebid.php', 'b_loaditems.php', and 'b_buynow.php'
 * Purpose: COS80021 Assignment 2 
 */

 "use strict";    //to indicate that the code should be executed in "strict mode" where no undeclared variables can be used.

 var xHRObject = false;

 if (window.XMLHttpRequest)
     xHRObject = new XMLHttpRequest();
 else if (window.ActiveXObject)
     xHRObject = new ActiveXObject("Microsoft.XMLHTTP");

//Refresh Every 10 Seconds - seems to need this long to avoid read and write conflict to the xml file
var myInterval = setInterval(loadItems, 10000);


function loadItems() 
{     
        xHRObject.open("POST", "b_loaditems.php", true);
        xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xHRObject.onreadystatechange = getDataFromXML;
        xHRObject.send();
}


function getDataFromXML()
{
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
		//   alert(xHRObject.responseXML);
		//   alert(xHRObject.responseText);
        var serverResponse = xHRObject.responseXML;
        var header = serverResponse.getElementsByTagName("ListedItem");
        var spantag = document.getElementById("itemsForAuction");
        spantag.innerHTML = "";
		
        for (var i=0; i<header.length; i++)
        {
			  
            var itemIDNode = header[i].firstChild;
            var itemnameNode = itemIDNode.nextSibling;
            var categoryNode = itemnameNode.nextSibling;
            var descNode = categoryNode.nextSibling;
            var buynowpriceNode = descNode.nextSibling;
            var bidpriceNode = buynowpriceNode.nextSibling;
            var durationNode = bidpriceNode.nextSibling;
            var statusNode = durationNode.nextSibling;         
        
            //https://www.delftstack.com/howto/javascript/create-table-javascript/
          
            // get the reference for the body
        
            // creates a <table> element and a <tbody> element
            var tbl = document.createElement("table");
            tbl.id = "auctionitem";
            var tBody = document.createElement("tBody");
            
            tbl.appendChild(tBody);

            // Adding the entire table to the body tag
            spantag.appendChild(tbl);


            // Creating and adding data to 1st row of the table    
            var row_1 = document.createElement('tr');
            var heading_1 = document.createElement('th');
            heading_1.innerHTML = "Item No:";
            var row_1_data_1 = document.createElement('td');
            row_1_data_1.innerHTML = itemIDNode.textContent;

            row_1.appendChild(heading_1);
            row_1.appendChild(row_1_data_1);

            // Creating and adding data to 2nd row of the table
            var row_2 = document.createElement('tr');
            var heading_2 = document.createElement('th');
            heading_2.innerHTML = "Item Name:";
            var row_2_data_1 = document.createElement('td');
            row_2_data_1.innerHTML = itemnameNode.textContent;

            row_2.appendChild(heading_2);
            row_2.appendChild(row_2_data_1);

            // Creating and adding data to 3rd row of the table
            var row_3 = document.createElement('tr');
            var heading_3 = document.createElement('th');
            heading_3.innerHTML = "Category:";
            var row_3_data_1 = document.createElement('td');
            row_3_data_1.innerHTML = categoryNode.textContent;

            row_3.appendChild(heading_3);
            row_3.appendChild(row_3_data_1);

            // Creating and adding data to 4th row of the table
            var row_4 = document.createElement('tr');
            var heading_4 = document.createElement('th');
            heading_4.innerHTML = "Description:";
            var row_4_data_1 = document.createElement('td');
            row_4_data_1.innerHTML = descNode.textContent;

            row_4.appendChild(heading_4);
            row_4.appendChild(row_4_data_1);

            // Creating and adding data to 5th row of the table
            var row_5 = document.createElement('tr');
            var heading_5 = document.createElement('th');
            heading_5.innerHTML = "Buy It Now Price:";
            var row_5_data_1 = document.createElement('td');
            row_5_data_1.innerHTML = buynowpriceNode.textContent;
            row_5_data_1.id = "buynowprice";

            row_5.appendChild(heading_5);
            row_5.appendChild(row_5_data_1);

            // Creating and adding data to 6th row of the table
            var row_6 = document.createElement('tr');
            var heading_6 = document.createElement('th');
            heading_6.innerHTML = "Bid Price:";
            var row_6_data_1 = document.createElement('td');
            row_6_data_1.innerHTML = bidpriceNode.textContent;

            row_6.appendChild(heading_6);
            row_6.appendChild(row_6_data_1);

            // Creating and adding data to 7th row of the table
            var row_7 = document.createElement('tr');
            var heading_7 = document.createElement('th');
            heading_7.innerHTML = "Auction Time Left:";
            var row_7_data_1 = document.createElement('td');
            //calculate the remaining auction time: diff between duration and current time
            //https://www.w3schools.com/js/js_dates.asp
            var duration = durationNode.textContent;
            var timeDiff = new Date(duration) - new Date();      //the result is milliseconds
             //depending on the resulted diff
            var timeDiffStr = convertMilliseconds(timeDiff);            //recreate a Date object with the milliseconds
            row_7_data_1.innerHTML = timeDiffStr;

            row_7.appendChild(heading_7);
            row_7.appendChild(row_7_data_1);

            // Creating and adding data to 8th row of the table 
            var row_8 = document.createElement('tr');
            var heading_8 = document.createElement('th');
            heading_8.innerHTML = "";
            var row_8_data_1 = document.createElement('td');
            //depending on the resulted auction time left
            if (timeDiff <= 0) {
                row_8_data_1.innerHTML = "<span class='failed'>Auction Expired!</span>";
            } else {
                //depending on the status of the item
                var status = statusNode.textContent;
                if (status == "sold") {
                    row_8_data_1.innerHTML = "<span class='confirmed'>SOLD</span>";
                // } else if (status == "failed") {
                //     row_8_data_1.innerHTML = "<span class='failed'>FAILED</span>";
                } else {
                    var itemID = row_1_data_1.innerHTML;
                    //add "Place Bid" button
                    var placebid = document.createElement('button');
                    placebid.value = itemID;
                    placebid.className = "tableBlueButton";
                    placebid.innerHTML = "Place Bid";
                    row_8_data_1.appendChild(placebid);
                    //add "Buy It Now" button
                    var buynow = document.createElement('button');
                    buynow.value = itemID;
                    placebid.className = "greenButton";
                    buynow.innerHTML = "Buy It Now";
                    row_8_data_1.appendChild(buynow);
                }
            }

            row_8.appendChild(heading_8);
            row_8.appendChild(row_8_data_1);
           
            tBody.appendChild(row_1);
            tBody.appendChild(row_2);
            tBody.appendChild(row_3);
            tBody.appendChild(row_4);
            tBody.appendChild(row_5);
            tBody.appendChild(row_6);
            tBody.appendChild(row_7);
            tBody.appendChild(row_8);
              
        }
        setEventListeners();
    }
       
}



function convertMilliseconds(milliSeconds){
    var days = Math.floor(milliSeconds/(24*60*60*1000));
    milliSeconds -= days*(24*60*60*1000);
    var hours = Math.floor(milliSeconds/(60*60*1000));
    milliSeconds -= hours*(60*60*1000);
    var minutes = Math.floor(milliSeconds/(60*1000));
    milliSeconds -= minutes*(60*1000);
    var seconds = Math.floor(milliSeconds/(1000));

    return (days + " days " + hours + " hours " + minutes + " minutes " + seconds + " seconds");
  
}


//https://softauthor.com/javascript-for-loop-click-event-issues-solutions/
function setEventListeners() {
    var buttons = document.getElementsByTagName("button");
    buttons = Array.from(buttons);    //buttons is a html Collection and is an array like object, not an Array. Can't use forEach()
       
    buttons.forEach(i => {
        var itemID = i.value;
        // alert(itemID);
        if (i.innerHTML == "Place Bid") {
            i.addEventListener("click", function() {
                placeBid(itemID);
            });
        }
        if (i.innerHTML == "Buy It Now") {
            i.addEventListener("click", function() {
                buyNow(itemID);
            });
        }
    })
}


function placeBid(id) {
    var bidprice = prompt("Please enter a Bid Price (we will round it up to 2 decimal points):");

    //test if user input a number in correct format
    var inputRegex = /^[0-9]*\.?[0-9]*$/    
    if (!inputRegex.test(bidprice)) {
        bidprice = prompt("Please enter a Bid Price (we will round it up to 2 decimal points):");
    } else {
        bidprice = parseFloat(bidprice).toFixed(2);   //parse the input price to 2 decimal points
        var argument = "value=";
        argument = argument + "&itemID="+id + "&bidprice="+bidprice; 
        
        xHRObject.open("POST", "b_placebid.php", true);
        xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
        xHRObject.onreadystatechange = getResult;
        xHRObject.send(argument);
    }
}

function buyNow(id) {
    var argument = "value=";
    argument = argument + "&itemID="+id; 
    
    xHRObject.open("POST", "b_buynow.php", true);
    xHRObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
    xHRObject.onreadystatechange = getResult;
    xHRObject.send(argument);
}


function getResult() {
    if ((xHRObject.readyState == 4) &&(xHRObject.status == 200))
    {
         // alert(xHRObject.responseText); 
        document.getElementById('bidResult').innerHTML = "";
        document.getElementById('bidResult').innerHTML = xHRObject.responseText;
        setTimeout(startInterval(), 5000);
    }
}





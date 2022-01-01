<?php
	/*
    */

    $HTML = "";
  
    if ((isset($_POST["firstname"])) && (isset($_POST["surname"])) && (isset($_POST["email"])) && (isset($_POST["password"]))) 
    {
        $firstname = trim($_POST["firstname"]);    //assign the values from the POST action into the variable
        $surname = trim($_POST["surname"]); 
        $email = trim($_POST["email"]); 
        $password = trim($_POST["password"]); 

        
        $emailValidated = isEmailValid($email);      
        if ($emailValidated == false) {
            $HTML = "<br><span class='failed'>$email is not a valid email address.</span></br>";
        } else {
            $emailValidated = isEmailUnique($email); 
            if ($emailValidated == false) {
                $HTML = "<br><span class='failed'>$email has been previously registered. Please use another one.</span></br>";     
            } else {
                require_once "utility.php";	
                $id = getUniqueID("customers.xml", "Customer", "CustomerID");
                $id = (string)$id;

                //start a session 
                session_start();
                $_SESSION["email"] = $email;
                $_SESSION["custID"] = $id;

                toXml($firstname, $surname, $email, $password, $id);
                
                // $HTML = "<br><span class='confirmed'>Welcome to use ShopOnline! Your customer id is ".$id.". Please go to 'Listing' for selling, or 'Bidding' for buying.</span></br>";
                $HTML = "valid";

                //send an email to customer to confirm registration
                $email_message = "Dear ".$firstname.", welcome to use ShopOnline! Your customer id is ".$id." and the password is ".$password."."; 
                $to = $email;
                $subject = "Welcome to ShopOnline";                        
                $header = "From: registration@shoponline.com.au \r\n";
                mail ($to,$subject,$email_message,$header, "-f 103196737@student.swin.edu.au"); 
            }  
        }
        echo $HTML;
    }


    function isEmailValid($email){
        //https://html.spec.whatwg.org/multipage/input.html#valid-e-mail-address
        if (preg_match("/^[a-zA-Z0-9.!#$%&'*+\/=?^_`{|}~-]+@[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?(?:\.[a-zA-Z0-9](?:[a-zA-Z0-9-]{0,61}[a-zA-Z0-9])?)*$/", $email))
        {   
            return true;
        } else {
            return false;
        }
    }


    function isEmailUnique($email){
        $xdoc = new DomDocument("1.0");     //create a new DomDocument Object
        $xdoc->Load("customers.xml");       //Load your pre-existing customers.xml        
        //get the root node of the document which is the Customers tag.
        $customersNode = $xdoc->documentElement;
        $customers = $xdoc->getElementsByTagName("Customer"); 
        foreach($customers as $node) {
            $emailNode = $node->getElementsByTagName("Email");
            $emailNodeValue = $emailNode->item(0)->nodeValue;
            if($emailNodeValue == $email) {
                return false;
            }
        }
        return true;
    }



    function toXml($fname, $sname, $eml, $pwd, $id)
    {
        
        //https://www.toolbox.com/tech/programming/question/add-another-group-of-elements-to-the-existing-xml-file-111509/
        
        $xdoc = new DomDocument("1.0");     //create a new DomDocument Object
        $xdoc->preserveWhiteSpace = false;   //need this for formatOutput to work - https://www.php.net/manual/en/domdocument.savexml.php
        $xdoc->Load("customers.xml");       //Load your pre-existing customers.xml
        $xdoc->formatOutput = true;         //output a nice format of XML
        //get the root node of the document which is the Customers tag.
        $customersNode = $xdoc->documentElement;

        //create a book node for the new book and append it
        $customerNodeElement=$xdoc->createElement("Customer");
        $customerNode=$customersNode->appendChild($customerNodeElement);

        //create each tag to go within the customer node and then append it to the customer node
        //1. the Firstname tag

        //create a Firstname element tag
        $firstname = $xdoc->createElement("Firstname");
        //append the Firstname tag to our customer node
        $firstnameNode=$customerNode->appendChild($firstname);
    
        //now create and append the text node within the Firstname tag

        //create a Firstname text node element using data from our form
        $firstnametextnode=$xdoc->createTextNode($fname);
        //append the Firstname data text node to the Firstname node
        $firstnameNode->appendChild($firstnametextnode);
            
        //2. the Surname tag
        $surname = $xdoc->createElement("Surname");
        $surnameNode=$customerNode->appendChild($surname);
        // the text node inside the Surname tag
        $surnametextnode=$xdoc->createTextNode($sname);
        $surnameNode->appendChild($surnametextnode);
    
        //3. the Email tag
        $email = $xdoc->createElement("Email");
        $emailNode=$customerNode->appendChild($email);
        // the text node inside the Email tag
        $emailtextnode=$xdoc->createTextNode($eml);
        $emailNode->appendChild($emailtextnode);

        //4. the Password tag
        $password = $xdoc->createElement("Password");
        $passwordNode=$customerNode->appendChild($password);
        // the text node inside the Password tag
        $passwordtextnode=$xdoc->createTextNode($pwd);
        $passwordNode->appendChild($passwordtextnode);

        //5. the CustomerID tag
        $custID = $xdoc->createElement("CustomerID");
        $custIDNode=$customerNode->appendChild($custID);
        // the text node inside the CustomerID tag
        $custIDtextnode=$xdoc->createTextNode($id);
        $custIDNode->appendChild($custIDtextnode);

        //Dump the internal XML tree back into the file
        $savedcorrectly= $xdoc->save("customers.xml");

    }
        
?>	
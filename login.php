<?php


    // header('Content-Type: text/xml');

    $xml = "customers.xml";
    $dom = DOMDocument::load($xml);
    $customer = $dom->getElementsByTagName("Customer"); 
    $HTML = "";

    
	if (isset($_POST["email"]))     
    {
		$email = trim($_POST["email"]);
		$password = trim($_POST["password"]);

        $valid = false;
        foreach ($customer as $node) {
            $c_email = $node->getElementsByTagName("Email");
            $c_email = $c_email->item(0)->nodeValue;
    
            $c_password = $node->getElementsByTagName("Password");
            $c_password = $c_password->item(0)->nodeValue;

            $c_ID = $node->getElementsByTagName("CustomerID");
            $c_ID = $c_ID->item(0)->nodeValue;

            if (($email == $c_email) && ($password == $c_password)) {
                session_start();
                $_SESSION["custID"] = $c_ID;           //set session variables to be used in other pages once login page is closed          
                $valid = true;
            }
        }
       
        
        if ($valid == true)
        {
            $HTML = "valid";
        } else {
            $HTML ="<br><span class='failed'>Invalid credentials. Please try again or 'Register' first!</span></br>";           
        }
            
        echo $HTML;

    }
?>
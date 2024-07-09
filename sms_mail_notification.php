<?php

session_start();

?>

<html>
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>HTML Contact form</title>
<link href="main.css" rel="stylesheet" type="text/css">
</head>
<body>

<?

if(isset($_POST["captcha"])&&$_POST["captcha"]!=""&&$_SESSION["code"]==$_POST["captcha"]) {
echo "<p>Thank you for your email!</p>";
} else {
die("<p>Wrong Code Entered</p><a href=\"javascript:history.go(-1)\">Go Back and Try Again</a>");
}

$option = $_REQUEST["option"];
$text = $_REQUEST["Sub_ject"];
$mname = $_REQUEST["first_name"];

// SMS and E-mail Notification Settings

// SMS SETTINGS

$user = "********"; // Change ********, and put your Proovl user ID
$token = "********"; // Change ********, and put your Proovl authentication token
$from = "********"; // Change ********, phone number under your Proovl account
$to = "********";        // Change ******** to your mobile number (with country code) notifications will be sent to this number 

// EMAIL SETTINGS
$email_to = "********";   // Change your-email@example.com, and put your E-mail
$email_subject = "New e-mail from contact form";  // You can change it optionally

		
        
	$created = date('Y-m-d H:i:s');
	
	$url = "http://www.proovl.com/api/send.php";

	$postfields = array(
		'user' => "$user",
		'token' => "$token",
		'from' => "$from",
		'to' => "$to",
		'text' => "New message from Contact Form $created, please check Your email! Name: $mname Subject: $text"
	);

if (!$curld = curl_init()) {
		exit;
	}

	curl_setopt($curld, CURLOPT_POST, true);
	curl_setopt($curld, CURLOPT_POSTFIELDS, $postfields);
	curl_setopt($curld, CURLOPT_URL,$url);
	curl_setopt($curld, CURLOPT_RETURNTRANSFER, true);

	$output = curl_exec($curld);

	curl_close ($curld);
	

if(isset($_POST['email'])) {
     
// E-mail Notification Settings
    
     global $email_to, $email_subject;
     
    function died($error) {
        // your error code can go here
        echo "We are very sorry, but there were error(s) found with the form you submitted.";
        echo "<p>These errors appear below:</p>";
        echo "<ul>".$error."</ul>";
        echo "<a href=\"javascript:history.go(-1)\">Please go back and fix these errors.</a>";
        die();
    }
     
    // validation expected data exists
    if(!isset($_POST['first_name']) ||
        !isset($_POST['Sub_ject']) ||
        !isset($_POST['email']) ||
        !isset($_POST['telephone']) ||
        !isset($_POST['comments'])) {
        died('We are sorry, but there appears to be a problem with the form you submitted.');       
    }
     
    $first_name = $_POST['first_name']; // required
    $Sub_ject = $_POST['Sub_ject']; // required
    $email_from = $_POST['email']; // required
    $telephone = $_POST['telephone']; // not required
    $comments = $_POST['comments']; // required
    
     
    $error_message = "";
    $email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
    
  if(!preg_match($email_exp,$email_from)) {
    $error_message .= "The E-mail address you entered does not appear to be valid.";
  }
  if(strlen($comments) < 1) {
    $error_message .= "The Comments you entered do not appear to be valid.";
  }
  
  if(strlen($Sub_ject) < 1) {
	$error_message .= "Empty field: Subject.";
	}
	if(strlen($first_name) < 1) {
	$error_message .= "Empty field: Name.";
	}

  if(strlen($error_message) > 0) {
    died($error_message);
  }
    $email_message = "Form details below.\n\n";
     
    function clean_string($string) {
      $bad = array("content-type","bcc:","to:","cc:","href");
      return str_replace($bad,"",$string);
      
    }
    
    $email_message .= "First Name: ".clean_string($first_name)."\r\n";
    $email_message .= "Subject: ".clean_string($Sub_ject)."\r\n";
    $email_message .= "Email: ".clean_string($email_from)."\r\n";
    $email_message .= "Telephone: ".clean_string($telephone)."\r\n";
    $email_message .= "Comments: ".clean_string($comments)."\r\n";
     
     
// create email headers
$headers = "From: $email_from\r\n"
    ."Reply-To: $email_from\r\n"
    ."MIME-Version: 1.0\r\n"
    ."Content-Type: text/plain; charset=UTF-8\r\n"
    ."X-Mailer: PHP/" . phpversion();

mail($email_to, $email_subject, $email_message, $headers);  

?>
<p>We will be in touch with you very soon.</p>
<a href="contact.html"><b>Back to Contact</b></a>

<?php
}
?>
</body>
</html>
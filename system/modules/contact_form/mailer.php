<?php

require_once('../../../config.php');

$system = new System();    
$system->Load();

if($_POST['Tester']=="" && $_POST['To']!="" && isset($_SESSION['contact_form'])){
    
    $message = "<h2>" . $system->title . " Contact Form Submission:</h2>";
    
    // Loop through all submitted fields
    foreach($_POST as $key=>$val){
        if($key!="To" && $key!="Tester"){
                $message .= "<strong>" . $key . ": </strong>" . stripslashes($val) . "<br>";
        }
    }
    
    $to_name = $system->title . " Website";
    $to_email = $_POST['To'];
    $from_name = $system->title . " Website";
    $from_email = "no-reply@" . $_SERVER["SERVER_NAME"];
    $reply_name = stripslashes($_POST['Name']);
    $reply_email = stripslashes($_POST['Email']);
    $subject = $system->title . " Contact Form Submission";
    
    $headers = "From: \"$from_name\" <$from_email>\n"; 
    $headers .= "Reply-To: \"$reply_name\" <$reply_email>\n"; 
    $headers .= "To: \"$to_name\" <$to_email>\n"; 
    $headers .= "Return-Path: <$from_email>\n"; 
    $headers .= "MIME-Version: 1.0\n"; 
    $headers .= "Content-Type: text/HTML; charset=ISO-8859-1n";
    
    if(mail($to_email, $subject, stripslashes($message), $headers)){
        echo 'pass';
    }else{
        echo 'fail';
    }

}else{
    echo 'fail';
}

?>

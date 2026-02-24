<?php

$myemail = 'you@yoursite.com';

$checkin     = $_POST['checkin'];
$checkout    = $_POST['checkout'];
$email       = $_POST['email'];
$subject     = $_POST['subject'];
$time        = $_POST['time'];
$persons     = $_POST['persons'];

$to            = $myemail;
$email_subject = "Room reservation";
$email_body    = "\n Check-In: $checkin \n Check-Out: $checkout \n Email: $email \n Subject: Room reservation \n Time: $time \n Persons: $persons";
$headers       = "From: $email";
 
mail($to, $email_subject, $email_body, $headers);

?>
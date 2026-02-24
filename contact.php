<?php
include('database.inc.php');
$msg="";
$ErrMsg = "";
$error_msg ="";
$phone_err = "";


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

session_start();

// Initialize variables to store form data
$Name = "";
$EmailID = "";
$Phonenumber = "";
$Subject = "";
$Message = "";

if(isset($_POST['submit'])){

  // collecting input values from frontend
  $Name =  trim(mysqli_real_escape_string($con, $_POST['name']));
  $EmailID = trim(mysqli_real_escape_string($con, $_POST['email']));
  $Phonenumber = trim(mysqli_real_escape_string($con, $_POST['phone']));
  $Subject = trim(mysqli_real_escape_string($con, $_POST['subject']));
  $Message = mysqli_real_escape_string($con, $_POST['message']);

  $name_err = "";

  if(empty($Name)) {
    $name_err .='*Name is required*';
  }

 

$error_msg = "";

// Validate email
$email_exp = '/^[A-Za-z0-9._%-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,4}$/';
if (!preg_match($email_exp, $EmailID)) {
    $error_msg .= 'Please Enter an valid Email Address';
} else {
    // Remove spaces from the email address
    $cleanedEmail = str_replace(' ', '', $EmailID);

    // Check if the cleaned email address is valid
    if (!filter_var($cleanedEmail, FILTER_VALIDATE_EMAIL)) {
        $error_msg .= 'You entered an invalid email<br/>';
    } else {
        // Use the cleaned email address
        $EmailID = $cleanedEmail;
    }
}


   // Validate phone number
   $phone_err = "";
   if(empty($Phonenumber)) {
       $phone_err .= '*Phone number is required*';
   } else {
       // Remove non-numeric characters from the phone number
       $cleanedPhone = preg_replace('/[^0-9+\-+()]/', '', $Phonenumber);

       // Check if the cleaned phone number meets the length criteria
       if (strlen($cleanedPhone) < 10 || strlen($cleanedPhone) > 20) {
           $phone_err .= '*Enter Valid Mobile Number*';
       } else {
           // Use the cleaned phone number
           $Phonenumber = $cleanedPhone;
       }
   }


    $html="<table><tr><td><strong>Name:</strong></td><td>$Name</td>
    </tr><tr><td><strong>Email:</strong></td><td>$EmailID</td></tr>
    <tr><td><strong>PhoneNumber:</strong></td><td>$Phonenumber</td></tr>
    <tr><td><strong>PhoneNumber:</strong></td><td>$Subject</td></tr>
    <tr><td><strong>Message:</strong></td><td>$Message</td></tr>
    </table>";
    

    if(empty($name_err) && empty($error_msg) && empty($phone_err)) {
      // Insert data into the database
      mysqli_query($con,"insert into contactform(Name, Email, PhoneNumber, Subject, Message) values('$Name', '$EmailID', '$Phonenumber', '$Subject', '$Message')");
      mysqli_close($con);
    //   header("Location:http://localhost/emcgl/index.php");
  
      // Send an email
      require 'phpmailer/src/PHPMailer.php'; // Adjust the path as per your project structure
      require 'phpmailer/src/SMTP.php';
  
      $mail = new PHPMailer(true);
  
      try {
        // SMTP settings
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com'; // Your SMTP server
        $mail->SMTPAuth = true;
        $mail->Username = 'sanjaysanju9448@gmail.com';
        $mail->Password = 'emjc clho qzte deve';
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
  
        // Sender and recipient
        $mail->setFrom('sanjaysanju9448@gmail.com', 'Sanjay Hirola');
        $mail->addAddress($EmailID, $Name); // Email and Name of the recipient
  
        // Email content
        $mail->isHTML(true);
        $mail->Subject = 'Thank you for submitting the form';
        $mail->Body = "Dear $Name,

        Thank you for reaching out to us. We appreciate your inquiry and the opportunity to assist you in your learning intiatives.
        
        Our team has received your message, and we will review the details you provided. One of our dedicated representatives will be in touch with you shortly to discuss your requirements in further details and address any queries you may have.
        
        In the meantime, feel free to explore our website www.bellibetta.com for more information about our offerings to gain better understanding.
        
        Should you have any immediate questions or require urgent assistance, please dont hesitate to contact us directly at +13072259336 or reply to this email.
        
        Thank you once again for considering Intell Edge Technologies. We look forward to the possibility of collaborating with you and contributing to your learning endeavors.
        
        Best Regards,
        Hari Kumar";
  
        // Send the email
        $mail->send();

          // Send an email to the SMTP user
    $mail->clearAddresses(); // Clear previous recipient addresses
    $mail->addAddress('sanjaysanju9448@gmail.com', 'sanjay'); // Replace with the SMTP user's email and name
    $mail->Subject = 'New Form Submission';
    $mail->Body = $html;

    // Send the email to the SMTP user
    $mail->send();
    // header("Location:http://localhost/Intell/index.php");

    
    
        $msg = "Form Submitted Successfully";
        // Set the formSubmitted flag in session
        $_SESSION['formSubmitted'] = true;
        // Reset form fields to initial state
        $Name = "";
        $EmailID = "";
        $Phonenumber = "";
        $Subject = "";
        $Message = "";

      } catch (Exception $e) {
        $msgg = "Form Submitted Successfully, but there was an error sending the email: {$mail->ErrorInfo}";
      }
    } else {
      $errmsg = "Please fill in the required fields.";
      
    }
}


?>


<!DOCTYPE html>
<html lang="zxx">
    <head>
        <meta charset="utf-8">
        <title>
            Contact Us | Best Homestay | Belli Betta
        </title>
        <link rel="icon" type="image/png" href="img/logo-dark.png">
        <meta content="Get in touch with us for the best homestay experience. Book your perfect accommodation today and enjoy a comfortable stay at an affordable price." name="description">
        <meta content="" name="author">
        <meta content="" name="keywords">
        <meta content="width=device-width, initial-scale=1, maximum-scale=1" name="viewport">
        <!-- ex nihilo || September 2022 -->
        <!-- style start -->
        <link href="css/plugins.css" media="all" rel="stylesheet" type="text/css">
        <link href="css/style.css" media="all" rel="stylesheet" type="text/css">
        <!-- style end -->
        <!-- google fonts start -->
        <link href="https://fonts.googleapis.com/css?family=Raleway:100,200,300,400,500,600,700,800,900%7CMontserrat:400,400i,500,500i,600,600i,700,700i,800,800i,900,900i" rel="stylesheet" type=
            "text/css">
        <!-- google fonts end -->
    </head>
    <body>
        <!-- preloader start -->
        <div class="preloader-bg"></div>
        <div id="preloader">
            <div id="preloader-status">
                <div class="preloader-position loader">
                    <span></span>
                </div>
            </div>
        </div>
        <!-- preloader end -->
        <!-- tst line start -->
        <div class="tst-line-t"></div>
        <div class="tst-line-l"></div>
        <div class="tst-line-r"></div>
        <div class="tst-line-b"></div>
        <!-- tst line end -->
        <!-- border top start -->
        <!-- container start -->
        <div class="container-fluid nopadding">
            <div class="extra-margin-border">
                <div class="border-top"></div>
            </div>
        </div>
        <!-- container end -->
        <!-- border top end -->
        <!-- navigation start -->
        <nav class="navbar navbar-fixed-top navbar-bg-switch">
            <!-- container start -->
            <div class="container-fluid nopadding">
                <div class="navbar-header fadeIn-element">
                    <!-- logo start -->
                    <div class="logo">
                        <a class="navbar-brand logo" href="index.html">
                            <!-- logo light start -->
                            <img alt="Logo" class="logo-light" src="img/logo-light.png">
                            <!-- logo light end -->
                            <!-- logo dark start -->
                            <img alt="Logo" class="logo-dark" src="img/logo-dark.png">
                            <!-- logo dark end -->
                        </a>
                    </div>
                    <!-- logo end -->
                </div>
                <!-- main navigation start -->
                <div class="main-navigation fadeIn-element">
                    <div class="navbar-header">
                        <button aria-expanded="false" class="navbar-toggle collapsed" data-target="#navbar-collapse" data-toggle="collapse" type="button"><span class="sr-only">Toggle
                        navigation</span> <span class="icon-bar"></span> <span class="icon-bar"></span> <span class="icon-bar"></span></button>
                    </div>
                    <div class="collapse navbar-collapse" id="navbar-collapse">
                        <!-- menu start -->
                        <ul class="nav navbar-nav navbar-right">
                            <li>
                                <a href="index.html">Home</a>
                            </li>
                            <li>
                                <a href="about.html">About</a>
                            </li>
                            <li>
                                <a href="rooms-suites.html">Rooms &amp; Suites</a>
                            </li>
                            <li>
                                <a href="restaurant.html">Restaurent</a>
                            </li>
                            <li class="dropdown">
                                <a href="#" class="dropdown-toggle disabled-custom" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false">Facilities <span class="caret"></span></a>
                                <ul class="dropdown-menu">
                                    <!-- <li><a class="nav-close" href="restaurant.html">Restaurant</a></li> -->
                                    <!-- <li><a class="nav-close" href="spa.html">Spa Center</a></li> -->
                                    <li><a class="nav-close" href="outdoorgames.html">Outdoor Games</a></li>
                                    <li><a class="nav-close" href="outdoorpool.html">Outdoor Pool</a></li>
                                </ul>
                            </li>
                            <li>
                                <a href="gallery.html">Gallery</a>
                            </li>
                            <!-- <li>
                                <a href="blog.html">blog</a>       
                            </li> -->
                            <li>
                                <a href="contact.php">Contact</a>
                            </li>
                        </ul>
                        <!-- menu end -->
                    </div>
                </div>
                <!-- main navigation end -->
            </div>
            <!-- container end -->
        </nav>
        <!-- navigation end -->
        <!-- home start -->
        <div class="upper-page bg-dark" id="home">
            <!-- hero bg start -->
            <div class="hero-fullscreen">
                <div class="hero-fullscreen-FIX">
                    <div class="hero-bg">
                        <!-- hero slider wrapper start -->
                        <div class="swiper-container-wrapper">
                            <!-- swiper container start -->
                            <div class="swiper-container swiper2">
                                <!-- swiper wrapper start -->
                                <div class="swiper-wrapper">
                                    <!-- swiper slider item start -->
                                    <div class="swiper-slide">
                                        <div class="swiper-slide-inner">
                                            <!-- swiper slider item IMG start -->
                                            <div class="swiper-slide-inner-bg contact-bg overlay overlay-dark">
                                            </div>
                                            <!-- swiper slider item IMG end -->
                                            <!-- swiper slider item txt start -->
                                            <div class="swiper-slide-inner-txt-2">
                                                <!-- section subtitle start -->
                                                <div class="blog-tag blog-tag-light fadeIn-element">Let's talk</div>
                                                <!-- section subtitle end -->
                                                <!-- divider start -->
                                                <div class="divider-m"></div>
                                                <!-- divider end -->
                                                <!-- section title start -->
                                                <h1 class="hero-heading hero-heading-home fadeIn-element">
                                                    Contact
                                                </h1>
                                                <!-- section title end -->
                                                <!-- divider start -->
                                                <div class="divider-m"></div>
                                                <!-- divider end -->
                                                <!-- button start -->
                                                <div class="more-wraper-center more-wraper-center-home fadeIn-element">
                                                    <a class="button button-effect" href="booking.html">
                                                        <div class="more-wraper-inner-home">
                                                            <i class="pulse"></i>
                                                            <div class="more-button-txt-center more-button-txt-center-home">
                                                                <span>Book now</span>
                                                            </div>
                                                        </div>
                                                    </a>
                                                </div>
                                                <!-- button end -->
                                            </div>
                                            <!-- swiper slider item txt end -->
                                        </div>
                                    </div>
                                    <!-- swiper slider item end -->
                                </div>
                                <!-- swiper wrapper end -->   
                            </div>
                            <!-- swiper container end -->
                        </div>
                        <!-- hero slider wrapper end -->
                    </div>
                </div>
            </div>
            <!-- hero bg end -->
            <!-- scroll indicator start -->
            <div class="scroll-indicator">
                <div class="scroll-indicator-wrapper">
                    <div class="scroll-line fadeIn-element"></div>
                </div>
            </div>
            <!-- scroll indicator end -->
        </div>
        <!-- home end -->
        <!-- vertical lines start -->
        <div class="vertical-lines-wrapper">
            <div class="vertical-lines"></div>
        </div>
        <!-- vertical lines end -->
        <!-- contact start -->
        <div id="contact" class="section-all bg-light">
            <!-- container start -->
            <div class="container-fluid">
                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                        <!-- line start -->
                        <div class="the-line"></div>
                        <!-- line end -->
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
            <!-- container start -->
            <div class="container">
                <!-- row start -->
                <div class="row">
                    <div class="post-spacing-3">
                        <!-- col start -->
                        <div class="col-md-4 col-sm-12">
                            <!-- section subtitle start -->
                            <div class="blog-tag">The Address</div>
                            <!-- section subtitle end -->
                            <!-- divider start -->
                            <div class="divider-m"></div>
                            <!-- divider end -->
                            <!-- section TXT start -->
                            <div class="section-txt-2">
                                <p>Atthibeedu Road, Sakleshpur, Karnataka 573165</p>
                            </div>
                            <!-- section TXT end -->
                            <!-- divider start -->
                            <div class="divider-l visible-mobile-devices"></div>
                            <!-- divider end -->
                        </div>
                        <!-- col end -->
                        <!-- col start -->
                        <div class="col-md-4 col-sm-12">
                            <!-- section subtitle start -->
                            <div class="blog-tag">The Email</div>
                            <!-- section subtitle end -->
                            <!-- divider start -->
                            <div class="divider-m"></div>
                            <!-- divider end -->
                            <!-- section TXT start -->
                            <div class="section-txt-2">
                                <p>
                                    <a class="link-effect" href="mailto:support@kaduhithlu.com">support@kaduhithlu.com</a>
                                </p>
                            </div>
                            <!-- section TXT end -->
                            <!-- divider start -->
                            <div class="divider-l visible-mobile-devices"></div>
                            <!-- divider end -->
                        </div>
                        <!-- col end -->
                        <!-- col start -->
                        <div class="col-md-4 col-sm-12">
                            <!-- section subtitle start -->
                            <div class="blog-tag">The Phone</div>
                            <!-- section subtitle end -->
                            <!-- divider start -->
                            <div class="divider-m"></div>
                            <!-- divider end -->
                            <!-- section TXT start -->
                            <div class="section-txt-2">
                                <p>+91 76769 59604</p>
                            </div>
                            <!-- section TXT end -->
                        </div>
                        <!-- col end -->
                    </div>
                </div>
                <!-- row end -->
                <!-- row start -->
                <div class="row" id="use-the-form">
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                        <!-- line start -->
                        <!-- <div class="the-line"></div> -->
                        <!-- line end -->
                        <!-- divider start -->
                        <!-- <div class="divider-l"></div> -->
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
                <!-- button start -->
                <div class="more-wraper-center contact-modal-launcher">
                    <div class="more-button-bg-center more-button-bg-center-dark more-button-bg-center-dark-close more-button-circle">
                    </div>
                    <div class="more-button-txt-center more-button-txt-center-close">
                        <span>Book Now</span>
                    </div>
                </div>
                <!-- button end -->
            </div>
            <!-- container end -->
            <!-- container start -->
            <div class="container-fluid">
                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- divider start -->
                        <!-- <div class="divider-l"></div> -->
                        <!-- divider end -->
                        <!-- line start -->
                        <!-- <div class="the-line"></div> -->
                        <!-- line end -->
                        <!-- divider start -->
                        <div class="divider-l-2"></div>
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
                <!-- row start -->
                <div class="row">
                    <!-- google maps wrapper start -->
                    <div id="google-maps-wrapper">
                        <!-- google maps start -->
                        <div class="google-maps">
                            <!-- <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3890.0116643842334!2d75.7593776!3d12.842522599999999!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba52101937fe043%3A0x7588aa0cc982fc5a!2sBELLI%20BETTA%20NATURE%20STAY!5e0!3m2!1sen!2sin!4v1704351097208!5m2!1sen!2sin" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe> -->
                            <iframe src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3887.203018608109!2d75.645408!3d12.9819581!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x3ba4d7145b17388b%3A0x82416e48a4d1ed0d!2sThe%20Kaduhithlu%20Resort%20-%20Sakleshpur!5e0!3m2!1sen!2sin!4v1708600000000" width="600" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
                        </div>
                        <!-- google maps end -->
                    </div>
                    <!-- google maps wrapper end -->
                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
        </div>
        <!-- contact end -->
        <!-- contact modal start -->
        <div class="contact-modal">
            <!-- container start -->
            <div class="container">
                <!-- center container start -->
                <div class="center-container-contact-modal">
                    <!-- center block start -->
                    <div class="center-block-contact-modal">
                        <!-- row start -->
                        <div class="row center-block-contact-modal-padding-top">
                            <!-- col start -->
                            <div class="col-lg-12">
                                <!-- section title start -->
                                <h2 class="hero-heading hero-heading-dark">
                                    Inquiries
                                </h2>
                                <!-- section title end -->
                                <!-- divider start -->
                                <div class="divider-l"></div>
                                <!-- divider end -->
                            </div>
                            <!-- col end -->
                        </div>
                        <!-- row end -->
                        <!-- row start -->
                        <div class="row contact-modal-wrapper">
                            <!-- col start -->
                            <div>
                                <!-- contact form start -->
                                <div id="contact-form">
                                    <form action="contact.php" method="POST" >
                                        <!-- col start -->
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <input class="requiredField name" id="name" name="name" placeholder="Name" type="text">
                                        </div>
                                        <!-- col end -->
                                        <!-- col start -->
                                        <div class="form-group col-sm-6 col-md-6 col-lg-6">
                                            <input class="requiredField email" id="email" name="email" placeholder="Email" type="email">
                                        </div>
                                        <!-- col end -->
                                        <!-- col start -->
                                        <div class=" form-group make-space">
                                            <input class="requiredField subject" id="subject" name="subject" placeholder="Subject" type="text">
                                        </div>
                                        <!-- col end -->
                                        <!-- col start -->
                                        <div class=" form-group make-space">
                                            <textarea class="requiredField message" id="message" name="message" placeholder="Message"></textarea>
                                        </div>
                                        <!-- col end -->
                                        <div>
                                            <!-- button start -->
                                            <div class="more-wraper-center more-wraper-center-form">
                                                <div class="more-button-bg-center more-button-bg-center-dark more-button-bg-center-dark-close more-button-circle"></div>
                                                <div class="more-button-txt-center more-button-txt-center-close">
                                                    <button type="submit" name="submit">
                                                    Submit
                                                    </button>
                                                </div>
                                            </div>
                                            <!-- button end -->
                                        </div>
                                    </form>
                                </div>
                                <!-- contact form end -->
                            </div>
                            <!-- col end -->
                        </div>
                        <!-- row end -->
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                        <!-- row start -->
                        <div class="row center-block-contact-modal-padding-bottom">
                            <div class="col-lg-12">
                                <!-- contact modal closer start -->
                                <div class="contact-modal-closer">
                                    <span class="ion-close"></span>
                                </div>
                                <!-- contact modal closer end -->
                            </div>
                        </div>
                        <!-- row end -->
                    </div>
                    <!-- center block end -->
                </div>
                <!-- center container end -->
            </div>
            <!-- container end -->
        </div>
        <!-- contact modal end --> 
        <!-- container start -->
        <div class="container-fluid">
            <!-- row start -->
            <div class="row">
                <!-- col start -->
                <div class="col-lg-12">
                    <!-- divider start -->
                    <div class="divider-l"></div>
                    <!-- divider end -->
                    <!-- line start -->
                    <div class="the-line"></div>
                    <!-- line end -->
                    <!-- divider start -->
                    <div class="divider-l"></div>
                    <!-- divider end -->
                </div>
                <!-- col end -->
            </div>
            <!-- row end -->
            <!-- row start -->
            <div class="row">
                <!-- col start -->
                <div class="col-lg-12">
                    <!-- parallax wrapper start -->
                    <div class="parallax-title">
                        <!-- HTML5 video URL start -->
                        <video playsinline autoplay muted loop>
                            <source src="./html5-videos-22/luxex/luxex.mp4" type="video/mp4">
                        </video>
                        <!-- HTML5 video URL end -->
                        <!-- page title start -->
                        <div class="parallax-title-content">
                            <!-- BELLI<br>
                            BETTA -->
                            KADUHITHLU
                        </div>
                        <!-- page title end -->
                    </div>
                    <!-- parallax wrapper end -->
                </div>
                <!-- col end -->
            </div>
            <!-- row end -->
        </div>
        <!-- container end -->
        <!-- footer start -->
        <div id="footer" class="section-all bg-dark bg-dark-blog">
            <!-- container start -->
            <div class="container-fluid">
                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                        <!-- line start -->
                        <div class="the-line"></div>
                        <!-- line end -->
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
                <!-- row start -->
                <div class="row footer-credits">
                    <!-- footer logo start -->
                    <div class="footer-credits-logo">
                        <a href="#"><img alt="Logo Footer" src="img/logo-dark.png"></a>
                    </div>
                    <!-- footer logo end -->
                    <!-- divider start -->
                    <div class="divider-l"></div>
                    <!-- divider end -->
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- social icons start -->
                        <div class="social-icons">
                            <ul>
                                <li>
                                    <a class="ion-social-twitter" href="#"><span>Twitter</span></a>                                
                                </li>
                                <li>
                                    <a class="ion-social-facebook" href="#"><span>Facebook</span></a>                                
                                </li>
                                <li>
                                    <a class="ion-social-instagram" href="#"><span>Instagram</span></a>                                
                                </li>
                            </ul>
                        </div>
                        <!-- social icons end -->
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- copyright start -->
                        <div class="copyright">
                            &copy; All Rights Reserved.
                        </div>
                        <!-- copyright end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
                <!-- row start -->
                <div class="row">
                    <!-- col start -->
                    <div class="col-lg-12">
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                        <!-- line start -->
                        <div class="the-line"></div>
                        <!-- line end -->
                        <!-- divider start -->
                        <div class="divider-l"></div>
                        <!-- divider end -->
                    </div>
                    <!-- col end -->
                </div>
                <!-- row end -->
            </div>
            <!-- container end -->
        </div>
        <!-- footer end -->
        <!-- to top arrow start -->
        <div class="to-top-arrow">
            <span class="ion-ios-arrow-up"></span>
        </div>
        <!-- to top arrow end -->
        <!-- scripts start -->
        <script src="js/plugins.js"></script> 
        <script src="js/luxex.js"></script>
        <!-- scripts end -->
    </body>
</html>
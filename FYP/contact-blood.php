<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require './PHPMailer/src/Exception.php';
require './PHPMailer/src/PHPMailer.php';
require './PHPMailer/src/SMTP.php';
session_start();
error_reporting(0);
include('includes/config.php');
if (isset($_POST['send'])) {
    $cid = $_GET['cid'];
    $name = $_POST['fullname'];
    $email = $_POST['email'];
    $contactno = $_POST['contactno'];
    $brf = $_POST['brf'];
    $message = $_POST['message'];

    $servername = "localhost"; // Change to your database server name
    $username = "root"; // Change to your database username
    $password = ""; // Change to your database password
    $dbname = "bbdms"; // Change to your database name

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }


    $sql = "SELECT FullName, EmailId FROM tblblooddonars WHERE id = $cid";

    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        // Output the email address
        while ($row = $result->fetch_assoc()) {
            // echo "Email: " . $row["EmailId"];
            // print($row["EmailId"]);
            $donarname = $row["FullName"];
            $donaremail = $row["EmailId"];
        }
    } else {
        echo "No email found for ID: " . $cid;
    }

    if (strlen($donaremail) > 1) {
        $sql = "INSERT INTO  tblbloodrequirer(BloodDonarID,name,EmailId,ContactNumber,BloodRequirefor,Message) VALUES(:cid,:name,:email,:contactno,:brf,:message)";
        $query = $dbh->prepare($sql);
        $query->bindParam(':cid', $cid, PDO::PARAM_STR);
        $query->bindParam(':name', $name, PDO::PARAM_STR);
        $query->bindParam(':email', $email, PDO::PARAM_STR);
        $query->bindParam(':contactno', $contactno, PDO::PARAM_STR);
        $query->bindParam(':brf', $brf, PDO::PARAM_STR);
        $query->bindParam(':message', $message, PDO::PARAM_STR);
        $query->execute();
        $lastInsertId = $dbh->lastInsertId();
        if ($lastInsertId) {
            $sub = "Blood Needed";
            $message1 = "Hello " . $donarname . ",<br>"
                . $name . " has requested your blood.<br>"
                . "You can contact them through email or phone number.<br>"
                . "Their email: " . $email . "<br>"
                . "Their phonenumber: " . $contactno . "<br>"
                . "Best regards,<br>"
                . "WEBBMS";
            $mail = new PHPMailer(true);
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'alistamang00@gmail.com';
            $mail->Password = 'fqkkuzosewgqcbtb';
            $mail->Port = 465;
            $mail->SMTPSecure = 'ssl';
            $mail->isHTML(true);
            $mail->setFrom('noreply@gmail.com');
            $mail->addAddress($donaremail);
            $mail->Subject = ($sub);
            $mail->Body = $message1;
            $mail->send();
            echo '<script>alert("Request has been sent. We will contact you shortly.")</script>';
            header("Location: search-donor.php"); // Change "reset_page.php" to the desired page URL
            exit();
        } else {
            echo "<script>alert('Something went wrong. Please try again.');</script>";
        }
        // echo "Sending mail to " . $donaremail;
    }
}
?>

<!DOCTYPE html>
<html lang="zxx">

<head>
    <title>Web Based Blood Bank | Blood Requerer </title>
    <!-- Meta tag Keywords -->

    <script>
        addEventListener("load", function() {
            setTimeout(hideURLbar, 0);
        }, false);

        function hideURLbar() {
            window.scrollTo(0, 1);
        }
    </script>
    <!--// Meta tag Keywords -->

    <!-- Custom-Files -->
    <link rel="stylesheet" href="css/bootstrap.css">
    <!-- Bootstrap-Core-CSS -->
    <link rel="stylesheet" href="css/style.css" type="text/css" media="all" />
    <!-- Style-CSS -->
    <link rel="stylesheet" href="css/fontawesome-all.css">
    <!-- Font-Awesome-Icons-CSS -->
    <!-- //Custom-Files -->

    <!-- Web-Fonts -->
    <link href="//fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i,800,800i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <link href="//fonts.googleapis.com/css?family=Roboto+Condensed:300,300i,400,400i,700,700i&amp;subset=cyrillic,cyrillic-ext,greek,greek-ext,latin-ext,vietnamese" rel="stylesheet">
    <!-- //Web-Fonts -->

</head>

<body>
    <?php include('includes/header.php'); ?>

    <!-- banner 2 -->
    <div class="inner-banner-w3ls">
        <div class="container">

        </div>
        <!-- //banner 2 -->
    </div>
    <!-- page details -->
    <div class="breadcrumb-agile">
        <div aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item">
                    <a href="index.php">Home</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page">Blood Needed Person</li>
            </ol>
        </div>
    </div>
    <!-- //page details -->

    <!-- contact -->
    <div class="agileits-contact py-5">
        <div class="py-xl-5 py-lg-3">
            <div class="w3ls-titles text-center mb-5">
                <h3 class="title">Contact For Blood</h3>
                <span>
                    <i class="fas fa-user-md"></i>
                </span>
            </div>
            <div class="d-flex">
                <div class="col-lg-5 w3_agileits-contact-left">
                </div>
                <div class="col-lg-7 contact-right-w3l">
                    <h5 class="title-w3 text-center mb-5">
                        <h5 class="title-w3 text-center mb-5">Fill following form for blood</h5>
                    </h5>
                    <form action="" method="post">
                        <div class="d-flex space-d-flex">
                            <div class="form-group grid-inputs">
                                <label for="recipient-name" class="contact-label">Your Name</label>
                                <input type="text" class="form-control1" id="name" name="fullname" placeholder="Please enter your name.">
                            </div>
                            <div class="form-group grid-inputs">
                                <label class="contact-label">Phone Number</label>
                                <input type="text" class="form-control1" id="phone" name="contactno" placeholder="Please enter your phone number.">
                            </div>
                        </div>

                        <div class="d-flex space-d-flex">
                            <div class="form-group grid-inputs">
                                <label for="contact-label"class="contact-label" >Email Address</label>
                                <input type="email" class="form-control1" id="email" name="email" required placeholder="Please enter your email address.">
                            </div>
                            <div class="form-group grid-inputs">
                                <label for="contact-label" class="contact-label">Blood Require For</label>
                                <select class="form-control1" id="for" name="brf">
                                    <option value="">Blood Require For</option>
                                    <option value="Father">Father</option>
                                    <option value="Mother">Mother</option>
                                    <option value="Brother">Brother</option>
                                    <option value="Sister">Sister</option>
                                    <option value="Others">Others</option>
                                </select>
                            </div>
                        </div>

                        <div class="form-group">
                            <label for="recipient-name" class="col-form-label">Message</label>
                            <textarea rows="10" cols="100" class="form-control1" id="message" name="message" placeholder="Please enter your message" maxlength="999" style="resize:none"></textarea>
                        </div>
                        <div class="form-group">
                            <input type="submit" value="Send Message" name="send">
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- //contact -->




    <?php include('includes/footer.php'); ?>

    <!-- Js files -->
    <!-- JavaScript -->
    <script src="js/jquery-2.2.3.min.js"></script>
    <!-- Default-JavaScript-File -->

    <!-- banner slider -->
    <script src="js/responsiveslides.min.js"></script>
    <script>
        $(function() {
            $("#slider4").responsiveSlides({
                auto: true,
                pager: true,
                nav: true,
                speed: 1000,
                namespace: "callbacks",
                before: function() {
                    $('.events').append("<li>before event fired.</li>");
                },
                after: function() {
                    $('.events').append("<li>after event fired.</li>");
                }
            });
        });
    </script>
    <!-- //banner slider -->

    <!-- fixed navigation -->
    <script src="js/fixed-nav.js"></script>
    <!-- //fixed navigation -->

    <!-- smooth scrolling -->
    <script src="js/SmoothScroll.min.js"></script>
    <!-- move-top -->
    <script src="js/move-top.js"></script>
    <!-- easing -->
    <script src="js/easing.js"></script>
    <!--  necessary snippets for few javascript files -->
    <script src="js/medic.js"></script>

    <script src="js/bootstrap.js"></script>
    <!-- Necessary-JavaScript-File-For-Bootstrap -->

    <!-- //Js files -->

</body>

</html>
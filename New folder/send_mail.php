<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
ini_set('max_execution_time', 0); // 0 = Unlimited
//send_mail.php
header("Content-Type: text/html; charset=ISO-8859-1");
require("sendgrid/sendgrid-php.php");
ini_set("display_errors",1);
//include_once  ('index.php');
$conn = mysqli_connect('localhost','gemc2020_webinar','4?HJ1wb+TkR3','gemc2020_webinar');
	//get mail template
	$sql = "SELECT template FROM mail_template where id ='1'";
	$result = mysqli_query($conn, $sql);
    if (mysqli_num_rows($result) > 0) {
        // output data of each row
        while($row = mysqli_fetch_assoc($result)) {
            $template = $row["template"];
        }
    }
if(isset($_POST["name"]))
{
$name = $_POST["name"];
$email1 = $_POST["email"];
// Here, you can also perform some database query operations with above values.
echo "Welcome ". $name ."!"; // Success Message
}

/*if(isset($_POST['fname1']))  (trim($_POST['fname1'])!= "") ;
		$email1 	= $_POST['fname1'];
	echo $email1;*/
if(isset($_POST['email_data']))
{
   
	require 'class/class.phpmailer.php';
	$output = '';
	foreach($_POST['email_data'] as $row)
	{
		$id =  $row["id"];
		$name = $row["name"];
        $email = $row["email"];
		$html = '<!DOCTYPE html><html><b>Dear Dr.  '.$name.',</b></br><br>';
            $html .= $template;
            $html .= '</html>';
		require 'vendor/autoload.php';

// Replace sender@example.com with your "From" address.
// This address must be verified with Amazon SES.
$sender = 'admin@eventminute.org';
$senderName = 'Mary Jane ';

// Replace recipient@example.com with a "To" address. If your account
// is still in the sandbox, this address must be verified.
$recipient = $email;

// Replace smtp_username with your Amazon SES SMTP user name.
$usernameSmtp = 'AKIA4XZKY4S5HKS6N7U2';

// Replace smtp_password with your Amazon SES SMTP password.
$passwordSmtp = 'BNC5kRDvgwj1jTGqyOpiOe54EfH7o2NwmWJeY4UTzxxk';

// Specify a configuration set. If you do not want to use a configuration
// set, comment or remove the next line.
$configurationSet = 'Events';

// If you're using Amazon SES in a region other than US West (Oregon),
// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
// endpoint in the appropriate region.
$host = 'email-smtp.us-east-1.amazonaws.com';
$port = 587;

// The subject line of the email
$subject = $row["fname"];

// The plain-text body of the email
$bodyText =  "Text";

// The HTML-formatted body of the email
$bodyHtml = $html;

$mail = new PHPMailer(true);

try {
    // Specify the SMTP settings.
    $mail->isSMTP();
    $mail->setFrom($sender, $senderName);
    $mail->Username   = $usernameSmtp;
    $mail->Password   = $passwordSmtp;
    $mail->Host       = $host;
    $mail->Port       = $port;
    //$mail->SMTPDebug = true;
    $mail->SMTPAuth   = true;
    $mail->SMTPSecure = 'tls';
    $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

    // Specify the message recipients.
    $mail->addAddress($recipient);
    $mail->AddReplyTo('Nursingcongress@innovinc.org', 'Nursing congress');
    // You can also add CC, BCC, and additional To recipients here.

    // Specify the content of the message.
    $mail->isHTML(true);
    $mail->Subject    = $subject;
    $mail->Body       = $bodyHtml;
    $mail->AltBody    = $bodyText;
    sleep(20);
    $mail->Send();
    echo "Email sent!" , PHP_EOL;
$sql = "UPDATE user SET lname = 1 WHERE id = '$id'";
                echo 'Done' ;
                $result = mysqli_query($conn, $sql); 
} catch (phpmailerException $e) {
    echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
} catch (Exception $e) {
    echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
}
	}
}
?>
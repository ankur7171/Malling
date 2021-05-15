<?php
ob_start();
session_start();
#include('includes/config.php');
include 'includes/MysqliDb.php';
include 'includes/conn.php';

extract($_REQUEST);

$from_email = $email; //sender email
$address = $db->getOne("address");
$recipient_email = $address["email"]; //recipient email
$subject = 'innovinc Join Us Form Details'; //subject of email

$message = '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tr>

<td align="left" valign="top" bgcolor="#999999"><table width="600" border="0" cellspacing="1" cellpadding="4">

  <tr align="left" valign="top" bgcolor="EDEDED">

    <td colspan="2" bgcolor="#fdd32a"><div align="center" class="dataregisterheading" style="color:#000;">innovinc Join Us Form Details </div></td>

    </tr>

    <tr align="left" valign="middle" bgcolor="#EDFFFF">

    <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Title</span>:</span></td>

    <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$title.'

    </span></td>

  </tr>

 <tr align="left" valign="middle" bgcolor="#EDFFFF">

    <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">First Name</span>:</span></td>

    <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$firstname.'

    </span></td>

  </tr>

   <tr align="left" valign="middle" bgcolor="#EDFFFF">

    <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Last Name</span>:</span></td>

    <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$lastname.'

    </span></td>

  </tr>

  <tr align="left" valign="middle" bgcolor="#fff">

    <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Company/University</span>:</span></td>

    <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$compuniversity.'</td>

  </tr>

     <tr align="left" valign="middle" bgcolor="#fff">

    <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Country</span>:</span></td>

    <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$country.'</td>

  </tr>

     <tr align="left" valign="middle" bgcolor="#fff">

    <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Email</span>:</span></td>

    <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$email.'</td>

  </tr>

     <tr align="left" valign="middle" bgcolor="#fff">

    <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Mobile Number</span>:</span></td>

    <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$mobileno.'</td>

  </tr>

  <tr align="left" valign="middle" bgcolor="#EDFFFF">

    <td valign="top" bgcolor="#FFFFFF"><span class="registerdata">Your Queries</span></td>

    <td valign="top" bgcolor="#FFFFFF" class="registercontent">'.$yourqueries.'</td>

  </tr>  

</table></td>

</tr>

</table>'; //message body

//get file details we need

$file_tmp_name    = $_FILES['uploadimage']['tmp_name'];

$file_name        = $_FILES['uploadimage']['name'];

$file_size        = $_FILES['uploadimage']['size'];

$file_type        = $_FILES['uploadimage']['type'];

$file_error       = $_FILES['uploadimage']['error'];

move_uploaded_file($_FILES['uploadimage']['tmp_name'],"images/joinus/".$file_name);  

if($file_error>0)

{

    die('upload error');

}

//read from the uploaded file & base64_encode content for the mail

$handle = fopen($file_tmp_name, "r");

$content = fread($handle, $file_size);

fclose($handle);

$encoded_content = chunk_split(base64_encode($content));

    //$boundary = md5("sanwebe");

    //header

    $headers = "MIME-Version: 1.0\r\n";

    $headers .= "From:".$from_email."\r\n";

    $headers .= "Reply-To: ".$from_email."" . "\r\n";

    $headers .= "Content-Type: multipart/mixed; boundary = $boundary\r\n\r\n";

    //plain text

    $body = "--$boundary\r\n";

    $body .= "Content-Type: text/html; charset=ISO-8859-1\r\n";

    $body .= "Content-Transfer-Encoding: base64\r\n\r\n";

    $body .= chunk_split(base64_encode($message));

    //attachment

    $body .= "--$boundary\r\n";

    $body .="Content-Type: $file_type; name=".$file_name."\r\n";

    $body .="Content-Disposition: attachment; filename=".$file_name."\r\n";

    $body .="Content-Transfer-Encoding: base64\r\n";

    $body .="X-Attachment-Id: ".rand(1000,99999)."\r\n\r\n";

    $body .= $encoded_content;

 $data = array("title"=>$title, "firstname"=>$firstname, "lastname"=>$lastname, "compuniversity"=>$compuniversity, "country"=>$country,
              "email"=>$email, "mobileno"=>$mobileno, "yourqueries"=>$yourqueries);
 $id = $db->insert("brocherdownload", $data);
 if ($id){
   	mail($recipient_email, $subject, $body, $headers);

	$to  = $email;

	$subject1 = 'Thank you for Brochure Download';

	$message = 'Thank you for downloading our conference brochure. Please drop an email or contact us if you need any assistance or guidance. ' . "\r\n\r\n" .'Regards,' . "\r\n" .
	'Innovinc Conferences';

	$headers = 'From:'.$recipient_email."\r\n" .

				'Reply-To: '.$recipient_email."\r\n" .

				'X-Mailer: PHP/' . phpversion();

	mail($to, $subject1, $message, $headers);

   	header("location:brochure-download.php");
 }
else{
	echo "<script>alert('Please try again.');</script>";
}	

?>


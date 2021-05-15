<?php
#ini_set("display_errors",1);
session_start();
if(!empty($_GET['orderID'])){
    // Include and initialize database class
    include "includes/MysqliDb.php";
    include "includes/conn.php";

    // Include and initialize paypal class
    include 'includes/PaypalExpress.class.php';
    $paypal = new PaypalExpress;
    
    // Get payment info from URL
    $orderID = $_GET['orderID'];
    $payerID = $_GET['payerID'];
    
    // Validate transaction via PayPal API
    $paymentCheck = $paypal->validate($orderID);
    // If the payment is valid and approved
    if($paymentCheck && $paymentCheck->status == 'COMPLETED'){
        // Get the transaction data
        $currency = 'USD';
        $details = array("status"=>"completed");
        if($_SESSION["user_info"]["type"] == "discountreg"){
            $table = "discountregistration";
        }
        else{
            $table = "userregistration";
        }
        $db->where('id', $_SESSION['reg_id']);
        $db->update($table, $details);
        $address = $db->getOne("address");
        $to = $address["email"]; //recipient email
		$subject = 'Innovinc Registration Paypal Details';
    	$message = '<table width="600" border="0" align="center" cellpadding="0" cellspacing="0"><tr>
    
        <td align="left" valign="top" bgcolor="#999999"><table width="600" border="0" cellspacing="1" cellpadding="4">
    
          <tr align="left" valign="top" bgcolor="EDEDED">
    
            <td colspan="2" bgcolor="#fdd32a"><div align="center" class="dataregisterheading" style="color:#000;">Innovinc Registration Paypal Details</div></td>
    
            </tr>
    
            <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Currency</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$currency.'
    
            </span></td>
    
          </tr>
     
         <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Designation</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$_SESSION["user_info"]["designation"].'
    
            </span></td>
    
          </tr>
    <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">First Name</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$_SESSION["user_info"]["firstname"].'
    
            </span></td>
    
          </tr> <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Email</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$_SESSION["user_info"]["useremail"].'
    
            </span></td>
    
          </tr>
    	   <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td width="264" valign="top" bgcolor="#FFFFFF"><span class="registerdata"><span class="bullet_text">Company Name</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#FFFFFF" class="registercontent"><span class="registercontent1">'.$_SESSION["user_info"]["company_name"].'
    
            </span></td>
    
          </tr>
    
          <tr align="left" valign="middle" bgcolor="#fff">
    
            <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Country</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$_SESSION["user_info"]["country"].'</td>
    
          </tr>
    
    	     <tr align="left" valign="middle" bgcolor="#fff">
    
            <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Mobile</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$_SESSION["user_info"]["mobile"].'</td>
    
          </tr>
    
    	     <tr align="left" valign="middle" bgcolor="#fff">
    
            <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Dietary Requirements</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$_SESSION["user_info"]["dietaryrequirements"].'</td>
    
          </tr>
    
    	     <tr align="left" valign="middle" bgcolor="#fff">
    
            <td width="264" valign="top" bgcolor="#fff"><span class="registerdata"><span class="lable">Please specify any diet</span>:</span></td>
    
            <td width="317" valign="top" bgcolor="#fff" class="registercontent">'.$_SESSION["user_info"]["ifvegany"].'</td>
    
          </tr>
       
    	 <tr align="left" valign="middle" bgcolor="#EDFFFF">
    
            <td valign="top" bgcolor="#FFFFFF"><span class="registerdata">User Amount</span></td>
    
            <td valign="top" bgcolor="#FFFFFF" class="registercontent">'.$_SESSION["user_info"]["totalamount"].'</td>
    
          </tr>   
    
        </table></td>
    
      </tr>
    
    </table>';	
     
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    
     
    $headers .= 'From: '.$to."\r\n";
     
    
    mail($to,$subject,$message,$headers);        
   }
    
    // Redirect to payment status page
    header("Location: success");
}else{
    // Redirect to the home page
    header("Location: failure");
}
?>
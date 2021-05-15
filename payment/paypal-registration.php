<?php
#ini_set("display_errors",1);
ob_start();
session_start();
extract($_REQUEST);
$_SESSION["user_info"] = $_REQUEST;
include('includes/config.php');
include "includes/MysqliDb.php";
include "includes/conn.php";
?>
<!DOCTYPE html>

<html lang="en">

<?php
//include( 'head.php' );
$rateuser=$totalamount + ($totalamount * 0.02);
if($_SESSION["user_info"]["type"] == "discountreg"){
    $table = "discountregistration";
    $_SESSION["user_info"]["amount"] = $rateuser;
    $details = array("designation"=>$_SESSION["user_info"]["designation"], "currency"=>"USD", "firstname"=>$_SESSION["user_info"]["firstname"], "useremail"=>$_SESSION["user_info"]["useremail"],
                "company_name"=>$_SESSION["user_info"]["company_name"], "country"=>$_SESSION["user_info"]["country"], "mobile"=>$_SESSION["user_info"]["mobile"], "dietaryrequirements"=>$_SESSION["user_info"]["dietaryrequirements"], 
                "ramount"=>$_SESSION["user_info"]["amount"], "ifvegany"=>$_SESSION["user_info"]["ifvegany"], "status"=>"cancelled");
}
else{
    $table = "userregistration";
    $radioamt += ($radioamt * 0.02);
    $accomadationamo += ($accomadationamo * 0.02);
    $_SESSION["user_info"]["radioamt"] = $radioamt;
    $_SESSION["user_info"]["accomadationamo"] = $accomadationamo;
    $details = array("designation"=>$_SESSION["user_info"]["designation"], "currency"=>"USD", "firstname"=>$_SESSION["user_info"]["firstname"], "useremail"=>$_SESSION["user_info"]["useremail"],
                "company_name"=>$_SESSION["user_info"]["company_name"], "country"=>$_SESSION["user_info"]["country"], "mobile"=>$_SESSION["user_info"]["mobile"], "dietaryrequirements"=>$_SESSION["user_info"]["dietaryrequirements"], 
                "rtype"=>$_SESSION["user_info"]["usertype"], "ramount"=>$_SESSION["user_info"]["radioamt"], "raccounttype"=>$_SESSION["user_info"]["accomadation"], 
                "raccountamount"=>$_SESSION["user_info"]["accomadationamo"], "ifvegany"=>$_SESSION["user_info"]["ifvegany"], "status"=>"cancelled");
}
                        
$registration = $db->insert($table, $details);
$_SESSION['reg_id'] = $registration;
?>
<!--
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
<script>
  paypal.Button.render({
    // Configure environment
    env: 'production',
    client: {
      production: 'AV23F6BBl8Rpd_o4hEsfh7cf2BdYtcIGSN7zJpkU6ZfTWju-LSMEMqNLe4GdMYGKUHCXQZYnlvO5W-C7',
     // production: 'demo_production_client_id'
    },
    // Customize button (optional)
     
    style: {
	layout: 'horizontal',
	fundingicons: 'true',
     size :"responsive",
	 shape: 'pill',
    },

    payment: function(data, actions) {
      return actions.payment.create({
		  
        transactions: [{
          amount: {
            total: <?php  echo $rateuser ?>,
            currency: 'USD'
          }
        }],
		redirect_urls: {
          return_url: '<?php echo SITE_URL;?>registration.php?msg=2',
          cancel_url: '<?php echo SITE_URL;?>registration.php?msg=1'
        }
      });
    },
    
    onAuthorize: function(data, actions) {
     return actions.payment.execute().then(function() {       
        actions.restart();
      });
    }
  }, '#paypal-button');

</script>-->
<body data-spy="scroll" data-target=".navbar-fixed-top">



	<?php include('header.php');?>
  <div class="inner-banner">

		<div class="container">

			<h3>Registration</h3>

		</div>

	</div>
 <div class="main-content">

		<div class="container">
        <div class="col-md-10 col-sm-12 col-xs-12 margin-auto">



            <div class="content padding-60">

             <h4 style="padding-bottom:16px; text-align:center;font-size:20px;">Your registration has been filed for $<?php  echo $rateuser ?> . Please check the details of the payment below.</h4>
             <table class="bordered table table-bordered payment">
								<thead>

								<tr>
									<th>Registration Details</th>
									<th>Amount</th>
								</tr>
								</thead>
								<tbody><tr>
									<td>Your Total Payment</td>
									<td>USD <?php  echo $rateuser ?> </td>
								</tr>        
								<tr>
									<td>Please click on the button to make the payment.</td>
									<td> <div id="paypal-button"></div></td>
								</tr>        
							</tbody>
             
             </table>

       



            </div>



          </div>
        </div></div>
 	 
 
    


<?php /*?><form id="paypal_checkout" action="https://www.paypal.com/cgi-bin/webscr" method="POST">
	 <input type="hidden" name="business" value="finance@innovincconferences.com"> 
	 
	<input name = "cmd" value = "_cart" type = "hidden">
	<input name = "upload" value = "1" type = "hidden">
	<input name = "no_note" value = "0" type = "hidden">
	<input name = "tax" value = "0" type = "hidden">
	<input name = "rm" value = "2" type = "hidden">
 
	<input name = "handling_cart" value = "0" type = "hidden">
	 
	
	 
	

	<!--<input type='hidden' name='notify_url' value='<?php //echo base_url()?>index.php/cart/process/update?orderid=<?php //echo $orderid?>' />-->
	
	<input type="hidden" name="item_name_1" value="<?php echo $usertype; ?>">
	<input type="hidden" name="item_number_1" value="234">
	<input type="hidden" name="amount_1" value="<?php echo $rateuser; ?>">
<!--	<input type="hidden" name="quantity_1" value="1">-->
	<input type="hidden" name="item_name_2" value="<?php echo $accomadation; ?>">
	<input type="hidden" name="amount_2" value="<?php echo $acamount; ?>">
	
	<input type="hidden" name="currency_code" value="<?php echo $currency; ?>">
	<input type='hidden' name='cancel_return' value='<?php echo SITE_URL;?>registration.php?msg=1' />
	 <input type='hidden' name='return' value='<?php echo SITE_URL;?>registration.php?msg=1'>
	<input type="hidden" name="notify_url" value="<?php echo SITE_URL;?>/paypall.php" /> 
	<input type="hidden" name="first_name" value="<?php echo $firstname; ?>" />
	<input type="hidden" name="night_phone_a" value="<?php echo $mobile; ?>" />
	<input type="hidden" name="payer_email" value="<?php echo $useremail; ?>" />
	<input type="hidden" name="payer_email" value="<?php echo $useremail; ?>" />
    <input type="hidden" name="pro_hosted" value="<?php echo SITE_URL; ?>" />

</form><?php */?>
	<?php include('footer.php');?>
<script src="https://www.paypal.com/sdk/js?client-id=AV23F6BBl8Rpd_o4hEsfh7cf2BdYtcIGSN7zJpkU6ZfTWju-LSMEMqNLe4GdMYGKUHCXQZYnlvO5W-C7"></script>
<script>
  $(document).ready(function(){
      paypal.Buttons({
        createOrder: function(data, actions) {
          return actions.order.create({
            purchase_units: [{
              amount: {
                value: <?= $rateuser; ?>
              }
            }]
          });
        },
        onApprove: function(data, actions) {
          // Capture the funds from the transaction
          return actions.order.capture().then(function(details) {
            alert('Transaction successfull');
            window.location = "process.php?orderID="+data.orderID+"&payerID="+data.payerID;
          });
        }
      }).render('#paypal-button');      
  });
</script>
<?php is_login(DOCROOT."login.html"); ?>
<ul>
<li><a href="/" title="Home"><?php echo $language['home']; ?> </a></li>
<li><span class="right_arrow"></span></li>
<li><a href="javascript:;" title="Order Details"><?php echo $language['order_details']; ?></a></li>    
</ul>

<h1><?php echo $page_title; ?></h1>

<div class="purchase_work">

<?php
session_start();
                            
$url = explode('paymentType=',$url_arr[1]);
$url = explode('&token',$url[1]);

if($url[0]=='cancel'){
	$_SESSION['reshash']='';
	echo '<strong>You have cancelled the paypal payment</strong>';
	die();
}

$resArray = $_SESSION['reshash'];
$ack = strtoupper($resArray["ACK"]);
if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || $resArray['Order Status'] == "This transaction has been approved.")
{
        echo "<h1>Your Payment has been successful</h1>";
}
else if($ack!="SUCCESS" && $ack!="SUCCESSWITHWARNING")
{
        echo "<h1>Your Payment has failed</h1>";
}

?>
<div style="text-align:center;background-color:#FFFF99;border:1px solid #DDDDDD;padding:5px;clear:both;">
<?php
if($ack=="SUCCESS" || $ack=="SUCCESSWITHWARNING" || $resArray['Order Status'] == "This transaction has been approved."){


	if(is_array($resArray) && count($resArray)>0) 
		{

			 foreach($resArray as $key => $value) {
					if($key=='TOKEN' || $key=='TIMESTAMP' || $key=='CORRELATIONID' || $key=='ACK' || $key=='TRANSACTIONID' || $key=='CURRENCYCODE' || $key=='AVSCODE' || $key=='AMT' || $key=='L_LONGMESSAGE0' || $key=='Invoice Number' || $key=='Authorization Code' || $key=='Credit card' || $key=='Billing Address' || $key=='Order Status' || $key=='Invoice Number'){    			

		    			echo "<div> $key:&nbsp; $value</div>";

				}
			 }
		}
?>
</div>
<a class="fr mt10" href="<?php echo DOCROOT;?>my-coupons.html" title="<?php echo strtoupper($language['mycoupons']); ?>" > 
	<span style="color:blue; font-weight:bold;font-size:14px;text-align:center;margin-top:20px;"><?php echo strtoupper($language["mycoupons"]); ?></span>
</a>
<?php
		}
else if($ack!="SUCCESS" and $ack!="SUCCESSWITHWARNING") {
 
?>

<?php  //it will print if any URL errors 
	if(isset($_SESSION['curl_error_no'])) { 
			$errorCode= $_SESSION['curl_error_no'] ;
			$errorMessage=$_SESSION['curl_error_msg'] ;	
			session_unset($_SESSION['curl_error_no']);
			session_unset($_SESSION['curl_error_msg']);	
?>

	<table width="500px">

	<tr>
		<td colspan="2">The PayPal API has returned an error!</td>
	</tr>
   
	<tr>
		<td>Error Number:</td>
		<td><?php echo $errorCode; ?></td>
	</tr>
	<tr>
		<td>Error Message:</td>
		<td><?php echo $errorMessage; ?></td>
	</tr>

	</table>

   <?php } else{
	 if(isset($resArray))
	 {

		if(is_array($resArray) && count($resArray)>0) 
		{
			 foreach($resArray as $key => $value) {
				if($key=='TOKEN' || $key=='TIMESTAMP' || $key=='CORRELATIONID' || $key=='ACK' || $key=='TRANSACTIONID' || $key=='CURRENCYCODE' || $key=='AVSCODE' || $key=='AMT' || $key=='L_LONGMESSAGE0'){

		    		echo "<div> $key:&nbsp;$value</div>";
				}

		    	 }

		}	

       	 }
       	 
    }
	
	 ?>
</div>
<a href="<?php echo DOCROOT;?>" title="<?php echo strtoupper($language['home']); ?>"  ><span style="color:blue; font-weight:bold;font-size:14px;text-align:center;margin-top:20px;">Back to Home page</span></a>
<?php	
       		
}

?>


</div>
<?php
//clear the session variables
$_SESSION['COUPONID']='';
$_SESSION['txn_id']='';
$_SESSION['deal_quantity']='';
$_SESSION['txn_amt']='';
$_SESSION['gift_recipient_id']='';
?>


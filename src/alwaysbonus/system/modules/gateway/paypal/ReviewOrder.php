<?php ob_start();
session_start();
/********************************************
ReviewOrder.php

This file is called after the user clicks on a button during
the checkout process to use PayPal's Express Checkout. The
user logs in to their PayPal account.

This file is called twice.

On the first pass, the code executes the if statement:

if (! isset ($token))

The code collects transaction parameters from the form
displayed by SetExpressCheckout.html then constructs and
sends a SetExpressCheckout request string to the PayPal
server. The paymentType variable becomes the PAYMENTACTION
parameter of the request string. The RETURNURL parameter
is set to this file; this is how ReviewOrder.php is called
twice.

On the second pass, the code executes the else statement.

On the first pass, the buyer completed the authorization in
their PayPal account; now the code gets the payer details
by sending a GetExpressCheckoutDetails request to the PayPal
server. Then the code calls GetExpressCheckoutDetails.php.

Note: Be sure to check the value of PAYPAL_URL. The buyer is
sent to this URL to authorize payment with their PayPal
account. For testing purposes, this should be set to the
PayPal sandbox.

Called by SetExpressCheckout.html.

Calls GetExpressCheckoutDetails.php, CallerService.php,
and APIError.php.

********************************************/
define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
require_once 'CallerService.php';
require_once(DOCUMENT_ROOT."/system/includes/docroot.php");
require_once(DOCUMENT_ROOT."/system/includes/dboperations.php");
	$USERID = $_SESSION['userid'];
	$token = $_REQUEST['token'];

/* An express checkout transaction starts with a token, that
   identifies to PayPal your transaction
   In this example, when the script sees a token, the script
   knows that the buyer has already authorized payment through
   paypal.  If no token was found, the action is to send the buyer
   to PayPal to first authorize payment
   */


//$token = $_REQUEST['token'];

if(! isset($token)) {

		//check deal quantity availability
		require_once(DOCUMENT_ROOT."/system/includes/transaction.php");
		$getvalue = split(",",$_POST['CUSTOM']);	
		$L_QTY0 = $getvalue[1];	
		$COUPONID = $getvalue[0];	
		check_deal_quantity($COUPONID,$_POST["friendname"],$_POST["friendemail"],$L_QTY0);

		/* The servername and serverport tells PayPal where the buyer
		   should be directed back to after authorizing payment.
		   In this case, its the local webserver that is running this script
		   Using the servername and serverport, the return URL is the first
		   portion of the URL that buyers will return to after authorizing payment
		   */
		   $serverName = $_SERVER['SERVER_NAME'];
		   $serverPort = $_SERVER['SERVER_PORT'];
		   //$url=dirname('http://'.$serverName.':'.$serverPort.$_SERVER['REQUEST_URI']);

		   $url=DOCROOT.'system/modules/gateway/paypal';

		   $currencyCodeType=$_POST['CURRENCYCODE'];
		   $paymentType=$_POST['PAYMENTACTION'];
		   $custom=$_POST['CUSTOM'];

	           $amt=$_POST['AMT']; 


            		 /* The returnURL is the location where buyers return when a
			payment has been succesfully authorized.
			The cancelURL is the location buyers are sent to when they hit the
			cancel button during authorization of payment during the PayPal flow
			*/

		   $returnURL =urlencode($url.'/ReviewOrder.php?currencyCodeType='.$currencyCodeType.'&paymentType='.$paymentType);
		   $cancelURL =urlencode(DOCROOT.'orderdetails.html?paymentType=cancel');

		 /* Construct the parameter string that describes the PayPal payment
			the varialbes were set in the web form, and the resulting string
			is stored in $nvpstr
			*/
           
           $nvpstr="";
		   
           /*
            * Setting up the Shipping address details
            */
           //$shiptoAddress = "&SHIPTONAME=$personName&SHIPTOSTREET=$SHIPTOSTREET&SHIPTOCITY=$SHIPTOCITY&SHIPTOSTATE=$SHIPTOSTATE&SHIPTOCOUNTRYCODE=$SHIPTOCOUNTRYCODE&SHIPTOZIP=$SHIPTOZIP";
           
           /*$nvpstr="&ADDRESSOVERRIDE=1$shiptoAddress&L_NAME0=".$L_NAME0."&L_NAME1=".$L_NAME1."&L_AMT0=".$L_AMT0."&L_AMT1=".$L_AMT1."&L_QTY0=".$L_QTY0."&L_QTY1=".$L_QTY1."&MAXAMT=".(string)$maxamt."&AMT=".(string)$amt."&ITEMAMT=".(string)$itemamt."&CALLBACKTIMEOUT=4&L_SHIPPINGOPTIONAMOUNT1=8.00&L_SHIPPINGOPTIONlABEL1=UPS Next Day Air&L_SHIPPINGOPTIONNAME1=UPS Air&L_SHIPPINGOPTIONISDEFAULT1=true&L_SHIPPINGOPTIONAMOUNT0=3.00&L_SHIPPINGOPTIONLABEL0=UPS Ground 7 Days&L_SHIPPINGOPTIONNAME0=Ground&L_SHIPPINGOPTIONISDEFAULT0=false&INSURANCEAMT=1.00&INSURANCEOPTIONOFFERED=true&CALLBACK=https://www.ppcallback.com/callback.pl&SHIPPINGAMT=8.00&SHIPDISCAMT=-3.00&TAXAMT=2.00&L_NUMBER0=1000&L_DESC0=Size: 8.8-oz&L_NUMBER1=10001&L_DESC1=Size: Two 24-piece boxes&L_ITEMWEIGHTVALUE1=0.5&L_ITEMWEIGHTUNIT1=lbs&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType."&CUSTOM=27";*/
           

            $query = "select * from coupons_coupons where coupon_id='$COUPONID'";
            $resultset = mysql_query($query);
	    while($row=mysql_fetch_array($resultset)){
			$DESC = html_entity_decode($row['coupon_name'],ENT_QUOTES);
		        $total_payable_amount = $row["coupon_value"]; 

			if(ctype_digit($total_payable_amount)) { 
				$total_payable_amount = $total_payable_amount;
			} 					  
			else { 

				$total_payable_amount = number_format($total_payable_amount, 2,',','');
				$total_payable_amount = explode(',',$total_payable_amount);
				$total_payable_amount = $total_payable_amount[0].'.'.$total_payable_amount[1];

			}                
		}

		//check whether amount payable is valid		
		
                $amt = ($total_payable_amount * $L_QTY0);
            

				   $nvpstr= "&AMT=".(string)$amt."&ReturnUrl=".$returnURL."&CANCELURL=".$cancelURL ."&CURRENCYCODE=".$currencyCodeType."&PAYMENTACTION=".$paymentType."&CUSTOM=".$custom."&QTY=".$L_QTY0."&L_NAME0=".$DESC."&L_AMT0=".$total_payable_amount."&L_QTY0=".$L_QTY0;

		   
           $nvpstr = $nvpHeader.$nvpstr;
           
		 	/* Make the call to PayPal to set the Express Checkout token
			If the API call succeded, then redirect the buyer to PayPal
			to begin to authorize payment.  If an error occured, show the
			resulting errors
			*/
		   $resArray=hash_call("SetExpressCheckout",$nvpstr);
		   $_SESSION['reshash']=$resArray;

		   $ack = strtoupper($resArray["ACK"]);

		   if($ack=="SUCCESS"){
					// Redirect to paypal.com here
					$token = urldecode($resArray["TOKEN"]);
					$payPalURL = PAYPAL_URL.$token;
					header("Location: ".$payPalURL);
				  } else  {
					 //Redirecting to APIError.php to display errors.
						//$location = DOCROOT."system/modules/gateway/paypal/APIError.php";
						//header("Location: $location");

						$location = DOCROOT.'orderdetails.html';
						header("Location: $location"); 
						exit;

					}
} else {
		 /* At this point, the buyer has completed in authorizing payment
			at PayPal.  The script will now call PayPal with the details
			of the authorization, incuding any shipping information of the
			buyer.  Remember, the authorization is not a completed transaction
			at this state - the buyer still needs an additional step to finalize
			the transaction
			*/

		   $token =urlencode( $_REQUEST['token']);

		 /* Build a second API request to PayPal, using the token as the
			ID to get the details on the payment authorization
			*/
		   $nvpstr="&TOKEN=".$token;

		   $nvpstr = $nvpHeader.$nvpstr;
		 /* Make the API call and store the results in an array.  If the
			call was a success, show the authorization details, and provide
			an action to complete the payment.  If failed, show the error
			*/
		   $resArray=hash_call("GetExpressCheckoutDetails",$nvpstr);

			//print_r($resArray); exit;

		   $_SESSION['reshash']=$resArray;
		   $ack = strtoupper($resArray["ACK"]);

		   if($ack == 'SUCCESS' || $ack == 'SUCCESSWITHWARNING'){
					require_once "GetExpressCheckoutDetails.php";
					
			  } else  {
				//Redirecting to APIError.php to display errors.
				//$location = "APIError.php";
				//$location = DOCROOT."system/modules/gateway/paypal/APIError.php";
				//header("Location: $location");
				$location = DOCROOT.'orderdetails.html';
				header("Location: $location"); 
				exit;

			  }
}
ob_flush();
?>

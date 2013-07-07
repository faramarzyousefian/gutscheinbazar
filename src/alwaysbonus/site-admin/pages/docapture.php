<?php ob_start(); ?>
<?php

define("DOCUMENT_ROOT",$_SERVER['DOCUMENT_ROOT']);
//get language config file
$admin_lang = $_SESSION["site_admin_language"];

if($admin_lang)
{
        include(DOCUMENT_ROOT."/system/language/admin_".$admin_lang.".php");
}
else
{

        include(DOCUMENT_ROOT."/system/language/admin_en.php");
}

	require_once(DOCUMENT_ROOT.'/system/includes/library.inc.php');

	$refid=$_GET['refid'];
	$invoice_id=$_GET['id'];

		//calling docapture method
		$url = DOCROOT.'system/modules/gateway/paypal/DoCaptureReceipt.php?invoiceid='.$_GET['id'];
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL,$url);
		curl_setopt($ch, CURLOPT_VERBOSE, 1);
		//turning off the server and peer verification(TrustManager Concept).
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
		//curl_setopt($ch, CURLOPT_POST, 0);
	        curl_exec($ch);
		curl_close($ch);


		// send email to the general users

				$queryString = "SELECT c.coupon_expirydate,coupons_cities.cityname, coupons_country.countryname, cs.shopname,cs.shop_address,cu.firstname,cu.lastname,cu.email, c.coupon_name, cp.coupon_validityid FROM coupons_purchase cp left join coupons_users cu on cp.coupon_userid = cu.userid left join coupons_coupons c on c.coupon_id=cp.couponid left join coupons_shops cs on cs.shopid = c.coupon_shop left join coupons_country on coupons_country.countryid=cs.shop_country left join coupons_cities on coupons_cities.cityid=cs.shop_city left join transaction_details td on td.ID=cp.transaction_details_id
				where cp.gift_recipient_id=0 and td.CAPTURED='1' and cp.transaction_details_id in (".$invoice_id.")";

						$resultSet = mysql_query($queryString);
		
						if(mysql_num_rows($resultSet)>0)
						{
							 while($row=mysql_fetch_array($resultSet))
							 {

								$shopname = html_entity_decode($row['shopname'], ENT_QUOTES);
								$shop_address = html_entity_decode($row['shop_address'], ENT_QUOTES);
								$cityname = html_entity_decode($row['cityname'], ENT_QUOTES);
								$countryname = html_entity_decode($row['countryname'], ENT_QUOTES);
								 $validityid = $row['coupon_validityid'];
								 $coupon_name = html_entity_decode($row['coupon_name'], ENT_QUOTES);
								 $to = $row['email'];
								 $name = ucfirst($row['firstname']).' '.ucfirst($row['lastname']);
								 $coupon_expirydate = $row['coupon_expirydate'];

								//getting subject and description variables
								$subject = $email_variables['transaction_emailsub'];
								$description = 	$email_variables['transaction_emaildesc'];
								$subject = str_replace("COUPONNAME",ucfirst($coupon_name),$subject);
								$description = str_replace("COUPONNAME",ucfirst($coupon_name),$description);

								$description = str_replace("EXPIREDATE",$coupon_expirydate,$description);

								$msg = $description = str_replace("VALIDITYID",$validityid,$description);

								$msg .= "<table style='margin-left:20px; font-family:Arial, Helvetica, sans-serif; font-size:12px; '>
									    <tr>
									      <td align='right'><strong>Shop Name :</strong></td>
									      <td align='left' class='padding-left:10px;'>".$shopname."</td>
									    </tr>	
									    <tr>
									      <td align='right'><strong>Address :</strong></td>
									      <td align='left' class='padding-left:10px;'>".$shop_address."</td>
									    </tr>
									    <tr>
									      <td align='right'><strong>City :</strong></td>
									      <td align='left' class='padding-left:10px;'>".$cityname."</td>
									    </tr>
									    <tr>
									      <td align='right'><strong>Country :</strong></td>
									      <td align='left' class='padding-left:10px;'>".$countryname."</td>
									    </tr>    
									    </table>";
									    
								 $from = SITE_EMAIL;

								/* GET THE EMAIL TEMPLATE FROM THE FILE AND REPLACE THE VALUES */
								$str = '';	
								$str = implode("",file(DOCROOT.'themes/_base_theme/email/email_all.html'));
								
								$str = str_replace("SITEURL",$docroot,$str);
								$str = str_replace("SITELOGO",$logo,$str);
								$str = str_replace("RECEIVERNAME",ucfirst($name),$str);
								$str = str_replace("MESSAGE",ucfirst($msg),$str);
								$str = str_replace("SITENAME",SITE_NAME,$str);

								$message = $str;
								$SMTP_STATUS = SMTP_STATUS;	
								
								if($SMTP_STATUS==1)
								{
	
									include(DOCUMENT_ROOT."/system/modules/SMTP/smtp.php"); //mail send thru smtp
								}
								else
								{

							     		// To send HTML mail, the Content-type header must be set
									$headers  = 'MIME-Version: 1.0' . "\r\n";
									$headers .= 'Content-type: ' .$php_content_type. "\r\n";
									// Additional headers
									$headers .= 'From: '.$from.'' . "\r\n";
									$headers .= 'Cc: '.$from. "\r\n";

									mail($to,$subject,$message,$headers);	
								}

		 		 
							 }
						 }

				// end email function


	$sql = "select TRANSACTIONID, AMT,COUPONID,L_QTY0 CURRENCYCODE,CAPTURED, ID from transaction_details where ID = '$invoice_id'";
	$result = mysql_query($sql);

	if(mysql_num_rows($result)!=0){

		while($row=mysql_fetch_array($result))
		{
			$CAPTURED_STATUS = $row['CAPTURED'];
		}
		if($CAPTURED_STATUS) {
			set_response_mes(1, $admin_language['paymentprocessed']);
		} 	
		else {
			mysql_query("update transaction_details set CAPTURED_ACK='Failed' where ID = '$invoice_id'");
			set_response_mes(-1, $admin_language['paymentfailed']); 	
		}

	}

	url_redirect($refid);
?>
<?php ob_flush(); ?>

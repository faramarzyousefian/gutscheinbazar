<?php ob_start();
session_start();
$logo = DOCROOT."site-admin/images/logo.png";

if(empty($cid))
{
        $coupon_details = mysql_query("select * from transaction_details where ID = '$txnid'");
        if(mysql_num_rows($coupon_details)>0)
        {
                $coupon_info = mysql_fetch_array($coupon_details);
                $cid = $coupon_info["COUPONID"];
        }
}
 
	require_once(DOCUMENT_ROOT."/system/includes/library.inc.php");

	$userid = $_SESSION['userid'];
	//get the deal purchased count 
        $queryString = "select sum(L_QTY0) as total from transaction_details where COUPONID = '$cid'";
        $result = mysql_query($queryString);
		if(mysql_num_rows($result))
		{
			$result = mysql_fetch_array($result);
		}
	$purchased_ccount = $result['total'];

	//get deal min user limit
        $queryString = "select coupon_value,coupon_shop,coupon_minuserlimit as minuser,coupon_maxuserlimit as maxuser from coupons_coupons where coupon_id='$cid'";
        $resultSet = mysql_query($queryString);
		while($row = mysql_fetch_object($resultSet)) 
		{
		    $minuserlimit = $row->minuser;
		    $coupon_shop_id = $row->coupon_shop;
		    $per_deal_cost = $row->coupon_value;
		}

		$coupon_min_quantity = $minuserlimit;

		//paypal payment processing
		if($coupon_min_quantity < $purchased_ccount)
		{
			$coupons_purchase_id = array();
			//process payment directly and deduct the amout 
			for($i=0;$i<$deal_quantity;$i++)
			{
				$queryString = "insert into coupons_purchase(transaction_details_id,couponid,coupon_userid) 
				values('$txnid','$cid','$userid')"; 
				mysql_query($queryString) or die(mysql_error());
				$coupons_purchase_id[] = mysql_insert_id();
			}


			$pay_capture_status = mysql_query("select pay_capture_status from coupons_coupons where coupon_id='$cid'");
			if(mysql_num_rows($pay_capture_status))
			{
				$pay_capture_status = mysql_fetch_array($pay_capture_status);
			}


				$send_mail = 1;
				$pay_capture_status = $pay_capture_status['pay_capture_status'];

			if($pay_capture_status)
			{
				//pay_capture_status already updated
				$direct_payment_capture = 1;

				//call docapture fn for direct payment for an user
				$invoiceid = $txnid;
				$url = DOCROOT.'system/modules/gateway/paypal/DoCaptureReceipt.php?invoiceid='.$invoiceid;
				$ch = curl_init();
				curl_setopt($ch, CURLOPT_URL,$url);
				curl_setopt($ch, CURLOPT_VERBOSE, 1);
				//turning off the server and peer verification(TrustManager Concept).
				curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
				curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
				curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
				//curl_setopt($ch, CURLOPT_POST, 0);
				$response = curl_exec($ch);
				curl_close($ch);

			}
			else
			{
				mysql_query("update coupons_coupons set pay_capture_status = '1' where coupon_id='$cid'");
				//call docapture fn

				$queryString = "select * from transaction_details where COUPONID = '$cid'";
				$result = mysql_query($queryString);
					if(mysql_num_rows($result))
					{
						while($row=mysql_fetch_array($result))
						{
							$invoiceid = $row['ID'];
							$url = DOCROOT.'system/modules/gateway/paypal/DoCaptureReceipt.php?invoiceid='.$invoiceid;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$url);
							curl_setopt($ch, CURLOPT_VERBOSE, 1);
							//turning off the server and peer verification(TrustManager Concept).
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
							//curl_setopt($ch, CURLOPT_POST, 0);
							$response = curl_exec($ch);
							curl_close($ch);

						}
					}


			} 

		}
		else
		{

			if($coupon_min_quantity == $purchased_ccount)
			{ 
				$coupons_purchase_id = array();
				for($i=0;$i<$deal_quantity;$i++)
				{
					$queryString = "insert into coupons_purchase(transaction_details_id,couponid,coupon_userid) 
					values('$txnid','$cid','$userid')"; 
					mysql_query($queryString) or die(mysql_error());
					$coupons_purchase_id[] = mysql_insert_id();
				}


				mysql_query("update coupons_coupons set pay_capture_status = '1' where coupon_id='$cid'");

				//release all payments from amount holded cards
				//call docapture fn
				//get list of invoice ids from transaction_details

				$queryString = "select * from transaction_details where COUPONID = '$cid'";
				$result = mysql_query($queryString);
					if(mysql_num_rows($result))
					{
						while($row=mysql_fetch_array($result))
						{
							$invoiceid = $row['ID'];
							$url = DOCROOT.'system/modules/gateway/paypal/DoCaptureReceipt.php?invoiceid='.$invoiceid;
							$ch = curl_init();
							curl_setopt($ch, CURLOPT_URL,$url);
							curl_setopt($ch, CURLOPT_VERBOSE, 1);
							//turning off the server and peer verification(TrustManager Concept).
							curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
							curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
							curl_setopt($ch, CURLOPT_RETURNTRANSFER,1);
							//curl_setopt($ch, CURLOPT_POST, 0);
							$response = curl_exec($ch);
							curl_close($ch);

						}

					}

				$send_mail = 1;

			} //end of if statement
			else
			{
				$coupons_purchase_id = array();
				//on holding the payments
				for($i=0;$i<$deal_quantity;$i++)
				{
					$queryString = "insert into coupons_purchase(transaction_details_id,couponid,coupon_userid) 
					values('$txnid','$cid','$userid')"; 
					mysql_query($queryString) or die(mysql_error());
					$coupons_purchase_id[] = mysql_insert_id();
				}


				//send mail to users, to intimate payment is in onhold
				$res = mysql_query("select u.email,u.firstname from transaction_details t left join coupons_users u on u.userid=t.USERID where t.COUPONID='$cid' and t.USERID='$userid'");
					if(mysql_num_rows($res) > 0)
					{ 
	  				        $from = SITE_EMAIL;

						$subject = $email_variables['transaction_onhold_subject'];
						$description = $msg = $email_variables['transaction_onhold_description'];

						while($row=mysql_fetch_array($res))
						{ 

							$to = $row["email"];
							$name = $row["firstname"];

							/* GET THE EMAIL TEMPLATE FROM THE FILE AND REPLACE THE VALUES */
							$str = '';	
						        $str = implode("",file(DOCROOT.'themes/_base_theme/email/email_all.html'));
						        
						        $str = str_replace("SITEURL",$docroot,$str);
						        $str = str_replace("SITELOGO",$logo,$str);
						        $str = str_replace("RECEIVERNAME",ucfirst($name),$str);
						        $str = str_replace("MESSAGE",ucfirst($description),$str);
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
								$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
								// Additional headers
								$headers .= 'From: '.$from.'' . "\r\n";
								$headers .= 'Cc: '.$from. "\r\n";

								mail($to,$subject,$message,$headers);	
							}


						}
					}

			}

		}

//common functionality for sending mail starts here

if($send_mail == 1)
{
				//generate coupon code and send it to buyers

				if($direct_payment_capture==1)
				{

					  $count = count($coupons_purchase_id); 
					  $value ='';
						  for($i=0;$i<$count;$i++)
						  {
						     $val = $coupons_purchase_id[$i];
						     $value.= $val.',';
						  }
					  $value = substr($value,0,strlen($value)-1);
					  $queryString = "SELECT * FROM coupons_purchase where coupon_purchaseid in (".$value.")";

				}
				else{
					$queryString = "SELECT * FROM coupons_purchase where couponid='$cid'";
				}

				$resultSet = mysql_query($queryString);
				if(mysql_num_rows($resultSet)>0)
				{
				    $name = $row["firstname"];
					while($noticia=mysql_fetch_array($resultSet))
					{    
		
					    
					    $vid = ranval();

						$coupon_purchaseid = $noticia["coupon_purchaseid"];
						$queryString = "update coupons_purchase set coupon_status='C',coupon_validityid_date=now(),coupon_validityid='$vid' where coupon_purchaseid='$coupon_purchaseid'";
						mysql_query($queryString)or die(mysql_error());


					}

		   	        }

				// send email to the general users

				if($direct_payment_capture==1)
				{
				$queryString = "SELECT c.coupon_expirydate,coupons_cities.cityname, coupons_country.countryname, cs.shopname,cs.shop_address,cu.firstname,cu.lastname,cu.email, c.coupon_name, cp.coupon_validityid FROM coupons_purchase cp left join coupons_users cu on cp.coupon_userid = cu.userid left join coupons_coupons c on c.coupon_id=cp.couponid left join coupons_shops cs on cs.shopid = c.coupon_shop left join coupons_country on coupons_country.countryid=cs.shop_country left join coupons_cities on coupons_cities.cityid=cs.shop_city left join transaction_details td on td.ID=cp.transaction_details_id
				where cp.couponid='$cid' and cp.gift_recipient_id=0 and td.CAPTURED='1' and cp.coupon_purchaseid in (".$value.")";
				}
				else
				{
				$queryString = "SELECT c.coupon_expirydate,coupons_cities.cityname, coupons_country.countryname, cs.shopname,cs.shop_address,cu.firstname,cu.lastname,cu.email, c.coupon_name, cp.coupon_validityid FROM coupons_purchase cp left join coupons_users cu on cp.coupon_userid = cu.userid left join coupons_coupons c on c.coupon_id=cp.couponid left join coupons_shops cs on cs.shopid = c.coupon_shop left join coupons_country on coupons_country.countryid=cs.shop_country left join coupons_cities on coupons_cities.cityid=cs.shop_city left join transaction_details td on td.ID=cp.transaction_details_id
				where cp.couponid='$cid' and cp.gift_recipient_id=0 and td.CAPTURED='1'";
				}
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
								 $coupon_expirydate = $row['coupon_expirydate'];
								 $coupon_name = html_entity_decode($row['coupon_name'], ENT_QUOTES);
								 $to = $row['email'];
								 $name = ucfirst($row['firstname']).' '.ucfirst($row['lastname']);

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
									$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
									$headers .= 'From: '.$from.'' . "\r\n";
									$headers .= 'Cc: '.$from. "\r\n";
									mail($to,$subject,$message,$headers);	

								}

		 		 
							 }
						 }

				// end email function

}
//common functionality for sending mail ends here

	//adding purchase count and referral earning amount details

	$coupon_info = get_coupon_code($cid);
	if(mysql_num_rows($coupon_info)>0)
	{
		while($row = mysql_fetch_array($coupon_info))
		{

			$offer_amount = $row["coupon_realvalue"] - $row["coupon_value"];

		}
	}

	//update saved amt during deal purchase
	$tot_cquantity = $deal_quantity;
	$tot_savedamt = $offer_amount * $tot_cquantity;
	$tot_savedamt = round($tot_savedamt);

	mysql_query("update coupons_purchase_status set coupons_purchased_count=coupons_purchased_count+$tot_cquantity, coupons_amtsaved=coupons_amtsaved+$tot_savedamt where id='1' ") or die(mysql_error());

	//update saved amt during deal purchase in current session
	$_SESSION["savedamt"] = $_SESSION["savedamt"] + $tot_savedamt;

//clear the session variables
$_SESSION['COUPONID']='';
$_SESSION['txn_id']='';
$_SESSION['deal_quantity']='';
$_SESSION['txn_amt']='';
$_SESSION['gift_recipient_id']='';
ob_flush();    
?>

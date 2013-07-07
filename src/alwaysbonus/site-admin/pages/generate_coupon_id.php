<?php ob_start();
require_once(DOCUMENT_ROOT.'/system/includes/dboperations.php');
require_once(DOCUMENT_ROOT.'/system/includes/docroot.php');
require_once(DOCUMENT_ROOT.'/system/includes/functions.php');
require_once(DOCUMENT_ROOT.'/system/includes/config.php');

$couponid= $url_arr[3];	

		$queryString = "SELECT coupon_purchaseid,coupon_userid FROM coupons_purchase where Coupon_amount_Status='T' and couponid=".$couponid;
		$resultSet = mysql_query($queryString);
		if(mysql_num_rows($resultSet)>0)
		{
                    
			while($noticia=mysql_fetch_array($resultSet))
			{    
		
				$vid=$noticia['coupon_purchaseid'].ranval()."_".$noticia['coupon_userid'];
				$queryString = "update coupons_purchase set coupon_status='C',coupon_validityid_date=now(),coupon_validityid='".$vid."' where coupon_purchaseid=".$noticia['coupon_purchaseid'];

		                    $resultSet1 = mysql_query($queryString)or die(mysql_error());


			}

			        $queryString ="update coupons_coupons set coupon_status='C' where coupon_id=".$couponid;
			        $resultSet2 = mysql_query($queryString)or die(mysql_error());

   	       }

// send email to the general users once offer started

$queryString = "SELECT coupons_cities.cityname, coupons_country.countryname, cs.shopname,cs.shop_address,cu.firstname,cu.lastname,cu.email, c.coupon_name, cp.coupon_validityid FROM coupons_purchase cp left join coupons_users cu on cp.coupon_userid = cu.userid left join coupons_coupons c on c.coupon_id=cp.couponid left join coupons_shops cs on cs.shopid = c.coupon_shop left join coupons_country on coupons_country.countryid=cs.shop_country left join coupons_cities on coupons_cities.cityid=cs.shop_city
where cp.Coupon_amount_Status='T' and cp.couponid='$couponid' and cp.gift_recipient_id=0 ";

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
				 $subject = "Your Purchased Coupon ".ucfirst($coupon_name)." offer has been Started";
				 
		 		 $msg = "Your Purchased Coupon ".ucfirst($coupon_name).", offer has been Started. Your Coupon Validity Id is ".$validityid.". You can avail the offer in the below shop by providing this Validity Id.<br/>";

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
				 send_email($from,$to,$subject,$msg,$name);		 		 
			 }
		 }

// end email function


// send email to the gift recipient users once offer started

$queryString = "SELECT coupons_cities.cityname, coupons_country.countryname, cs.shopname,cs.shop_address,cu.firstname,cu.lastname,cu.email as senderemail, grd.name,c.coupon_name, cp.coupon_validityid,grd.email FROM coupons_purchase cp left join coupons_users cu on cp.coupon_userid = cu.userid left join coupons_coupons c on c.coupon_id=cp.couponid left join gift_recipient_details grd on grd.id=cp.gift_recipient_id left join coupons_shops cs on cs.shopid = c.coupon_shop left join coupons_country on coupons_country.countryid=cs.shop_country left join coupons_cities on coupons_cities.cityid=cs.shop_city
where cp.Coupon_amount_Status='T' and cp.couponid='$couponid' and cp.gift_recipient_id<>0 ";

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
				 $name = ucfirst($row['name']);
				 
				 $gift_sendername = ucfirst($row['firstname']).' '.ucfirst($row['lastname']);				 

				 $gift_senderemail = $row['senderemail'];
				 				 
				 $subject = "You Got Coupon Gift from your Friend";
				 				 
		 		 $msg = "You Got Coupon Gift from your Friend ".$gift_sendername.". Your Gift Coupon ".ucfirst($coupon_name).", offer has been Started. Your Coupon Validity Id is ".$validityid.". You can avail the offer in the below shop by providing this Validity Id.<br/><br/>";
		 		 
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
				 send_email($from,$to,$subject,$msg,$name);		 		 
			 }
		 }

// end email function


	set_response_mes(1, $admin_language['offerstarted']);
	
	if($_SESSION["userrole"] == 3)
		$redirect_url = DOCROOT."admin/view/rep/myuploadcoupon/"; 	
	else
		$redirect_url = DOCROOT."admin/view/rep/coupons/";
		
	url_redirect($redirect_url);
ob_flush();
?>



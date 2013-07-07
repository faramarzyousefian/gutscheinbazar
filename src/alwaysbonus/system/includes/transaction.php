<?php ob_start();
session_start();
require_once($_SERVER['DOCUMENT_ROOT']."/system/includes/library.inc.php");


    function calculate_coupon_amt($c_id)
    {
	    $query = "select * from coupons_coupons where coupon_id='$c_id'";
	    $resultset = mysql_query($query);

		if(mysql_num_rows($resultset)>0)
		{
			while($row = mysql_fetch_array($resultset))
			{
				$coupon_code = $row["coupon_id"];
				$coupons_realvalue = $row["coupon_realvalue"];
				$coupon_offer = $row["coupon_offer"];
				$offer_amount = (($coupons_realvalue * $coupon_offer)/100); 
				$total_payable_amount = $coupons_realvalue - $offer_amount; 
			}
		}

    }

	function deal_ranval()
	{ 
	    srand(time()); 
	    $random_letter_lcase = chr(rand(ord("a"), ord("z"))); 
	    $random_letter_ucase = chr(rand(ord("A"), ord("Z")));
	    $random_letter_number = chr(rand(ord("0"), ord("9")));
	    $random_letter_lcase1 = chr(rand(ord("a"), ord("z")));  
	    $randomvalue= "_".$random_letter_lcase.$random_letter_ucase.$random_letter_number.$random_letter_lcase1;
	    return $randomvalue;
	}

	function check_deal_quantity($cid='',$friendname='',$friendemail='',$cquantity='',$mobile_pay = '')
	{

		//get the deal purchased count 
		$queryString = "select sum(L_QTY0) as total from transaction_details where COUPONID = '$cid'";
		$result = mysql_query($queryString);
			if(mysql_num_rows($result))
			{
				$result = mysql_fetch_array($result);
			}
		$purchased_ccount = $result['total'];

		//get deal min user limit
		$queryString = "select coupon_minuserlimit as minuser,coupon_maxuserlimit as maxuser from coupons_coupons where coupon_id='$cid'";
		$resultSet = mysql_query($queryString);
			while($row = mysql_fetch_object($resultSet)) 
			{
			    $minuserlimit = $row->minuser;
			    $maxuserlimit = $row->maxuser;
			}

			$available_deals = $maxuserlimit - $purchased_ccount;

			if($purchased_ccount == $maxuserlimit){
				$query = "update coupons_coupons set coupon_status ='C' where coupon_id='$cid'";
				$result = mysql_query($query) or die(mysql_error());
			}
		
			//check deal availability
			if($available_deals<$cquantity)
			{

				if($available_deals > 0) {
					set_response_mes(-1, "Sorry, ".$available_deals." deals only available to purchase."); }
				else {
					set_response_mes(-1, "Sorry, all the deals are purchased."); 
				}
				
				if(empty($mobile_pay))
				{
					if($friendname!='' && $friendemail!='')
					{
						url_redirect(DOCROOT."purchase.html?cid=".$cid."&type=gift");
					}
					else
					{
						url_redirect(DOCROOT."purchase.html?cid=".$cid);
					}
				}
				else
				{
					return -1; // -1 => no deals available
				}

			}

	}

	function update_gift_recipient($coupons_purchase_id='')
	{
		  $count = count($coupons_purchase_id); 
		  $value ='';
			  for($i=0;$i<$count;$i++)
			  {
			     $val = $coupons_purchase_id[$i];
			     $value.= $val.',';
			  }
		  $value = substr($value,0,strlen($value)-1);

		mysql_query("update coupons_purchase set gift_recipient_id='$gift_recipient_id' where coupon_purchaseid in ($value) ") or die(mysql_error());

	}

	function check_deal_status($cid='')
	{
		//get the deal purchased count 
		$queryString = "select sum(L_QTY0) as total from transaction_details where COUPONID = '$cid'";
		$result = mysql_query($queryString);
			if(mysql_num_rows($result))
			{
				$result = mysql_fetch_array($result);
			}
		$purchased_ccount = $result['total'];

		//get deal min user limit
		$queryString = "select coupon_minuserlimit as minuser,coupon_maxuserlimit as maxuser from coupons_coupons where coupon_id='$cid'";
		$resultSet = mysql_query($queryString);
			while($row = mysql_fetch_object($resultSet)) 
			{
			    $minuserlimit = $row->minuser;
			    $maxuserlimit = $row->maxuser;
			}

			if($purchased_ccount == $maxuserlimit){
				$query = "update coupons_coupons set coupon_status ='C' where coupon_id='$cid'";
				$result = mysql_query($query) or die(mysql_error());
			}

	}

ob_flush();    
?>
